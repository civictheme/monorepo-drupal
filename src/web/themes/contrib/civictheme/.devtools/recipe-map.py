#!/usr/bin/env python3
"""Recipe mapping and dependency-graph tool for the starterkit conversion.

Assigns every config item shipped by the theme to its owning recipe, builds
the recipe-level dependency graph from the config items' own `dependencies`
sections, and validates that each recipe's needs are satisfied by its declared
`recipes:` chain and by the `civictheme_site` composition order.

Usage, from the theme directory:

    python3 .devtools/recipe-map.py            # report + validate
    python3 .devtools/recipe-map.py --manifest # write .devtools/recipe-manifest.json

The manifest is the single source of truth for which recipe owns which config
item. The config export tooling reads it to route exported config back into
the recipe directories, and the one-time move of the shipped config used it
the same way.
"""

import argparse
import json
import re
import sys
from collections import defaultdict
from pathlib import Path

import yaml

THEME_DIR = Path(__file__).resolve().parent.parent

# Modules whose config a recipe may depend on without shipping it: installed
# by the recipe's own `install:` list (or a composed recipe's), their default
# config arrives with them. The module installer resolves module-level
# dependencies itself, so these lists name the top-level features a recipe
# turns on, plus whatever its config directly depends on.
#
# Recipe name -> modules the recipe installs.
RECIPE_INSTALL = {
    # node: the linkit profile's content matcher plugin.
    "civictheme_text_formats": ["ckeditor5", "editor", "filter", "linkit", "node"],
    "civictheme_date_formats": [],
    "civictheme_taxonomy": ["taxonomy", "path"],
    "civictheme_image_styles": ["image", "focal_point"],
    "civictheme_media": [
        "media",
        "media_library",
        "file",
        "link",
        "layout_builder",
        "path",
        "views",
    ],
    "civictheme_menus": [],
    "civictheme_node_types": [
        "node",
        "menu_ui",
        "datetime",
        "datetime_range",
        "media",
        "options",
        "taxonomy",
    ],
    "civictheme_paragraphs": [
        "paragraphs",
        "entity_reference_revisions",
        "options",
        "link",
        "datetime",
        "datetime_range",
        "field_group",
        "views",
    ],
    "civictheme_webform": ["webform"],
    "civictheme_page": ["pathauto", "path", "layout_builder_restrictions"],
    "civictheme_event": ["pathauto", "path", "layout_builder_restrictions"],
    "civictheme_alert": ["pathauto", "path", "rest", "serialization", "views"],
    "civictheme_block_types": ["block_content", "block"],
    "civictheme_editorial_workflow": ["content_moderation", "workflows", "views"],
    "civictheme_roles": ["contextual", "redirect", "shortcut"],
    "civictheme_search": ["search_api", "search_api_db", "views"],
}

# Declared composition: recipe name -> recipes it applies first.
#
# Differences from the original plan table, proven by the config data:
# - text_formats composes media: the rich text format's media_embed filter
#   depends on the theme-shipped core.entity_view_mode.media.embedded.
# - page and block_types compose webform: their paragraph reference fields
#   enumerate the civictheme_webform paragraph bundle as a target.
# - editorial_workflow composes block_types and media (not just the node
#   bundles): the workflow moderates block_content and media bundles too.
RECIPE_COMPOSES = {
    "civictheme_text_formats": ["civictheme_media"],
    "civictheme_date_formats": [],
    "civictheme_taxonomy": [],
    "civictheme_image_styles": [],
    "civictheme_media": ["civictheme_taxonomy", "civictheme_image_styles"],
    "civictheme_menus": [],
    "civictheme_node_types": [],
    "civictheme_paragraphs": [
        "civictheme_node_types",
        "civictheme_media",
        "civictheme_taxonomy",
        "civictheme_text_formats",
        "civictheme_date_formats",
    ],
    "civictheme_webform": ["civictheme_paragraphs"],
    "civictheme_page": [
        "civictheme_node_types",
        "civictheme_paragraphs",
        "civictheme_webform",
        "civictheme_taxonomy",
        "civictheme_media",
        "civictheme_menus",
    ],
    "civictheme_event": [
        "civictheme_node_types",
        "civictheme_paragraphs",
        "civictheme_taxonomy",
        "civictheme_media",
    ],
    "civictheme_alert": ["civictheme_node_types", "civictheme_text_formats"],
    "civictheme_block_types": [
        "civictheme_paragraphs",
        "civictheme_webform",
        "civictheme_media",
    ],
    "civictheme_editorial_workflow": [
        "civictheme_page",
        "civictheme_event",
        "civictheme_alert",
        "civictheme_block_types",
        "civictheme_media",
    ],
    "civictheme_roles": [
        "civictheme_page",
        "civictheme_event",
        "civictheme_alert",
        "civictheme_webform",
        "civictheme_block_types",
        "civictheme_editorial_workflow",
    ],
    "civictheme_search": ["civictheme_page", "civictheme_event"],
}

# The site-starter composition, in application order. civictheme_search is a
# deliberate add-on, applied separately.
SITE_RECIPE = [
    "civictheme_taxonomy",
    "civictheme_image_styles",
    "civictheme_media",
    "civictheme_text_formats",
    "civictheme_date_formats",
    "civictheme_menus",
    "civictheme_node_types",
    "civictheme_paragraphs",
    "civictheme_webform",
    "civictheme_page",
    "civictheme_event",
    "civictheme_alert",
    "civictheme_block_types",
    "civictheme_editorial_workflow",
    "civictheme_roles",
]

# Config prefix -> providing module. A config entity never lists its own
# provider in its dependencies, so the provider has to be inferred to check
# module coverage.
PROVIDERS = {
    "block.block": "block",
    "block_content.type": "block_content",
    "core.base_field_override": "system",
    "core.date_format": "system",
    "core.entity_form_display": "system",
    "core.entity_form_mode": "system",
    "core.entity_view_display": "system",
    "core.entity_view_mode": "system",
    "editor.editor": "editor",
    "field.field": "field",
    "field.storage": "field",
    "filter.format": "filter",
    "image.style": "image",
    "linkit.linkit_profile": "linkit",
    "media.type": "media",
    "node.type": "node",
    "paragraphs.paragraphs_type": "paragraphs",
    "pathauto.pattern": "pathauto",
    "search_api.index": "search_api",
    "search_api.server": "search_api",
    "system.menu": "system",
    "taxonomy.vocabulary": "taxonomy",
    "user.role": "user",
    "views.view": "views",
    "webform.webform": "webform",
    "workflows.workflow": "workflows",
}

# Modules present on any Drupal site our recipes apply to (or pulled in
# transitively by every realistic chain): not tracked for coverage.
ALWAYS_AVAILABLE = {"core", "system", "user", "field", "text", "filter"}

# Simple (non-entity) module config that already exists once its module is
# installed: it cannot ship as a recipe config file (the recipe system's
# strict pre-existing check would reject the mismatch) and becomes a
# `config: actions:` simpleConfigUpdate entry on the owning recipe instead.
ACTIONS = {
    "media.settings": "civictheme_media",
}

# Shipped config that is byte-identical to the providing module's defaults:
# dropped outright, nothing to ship or act on.
DROPPED = {
    "search_api.settings",
    "search_api_db.settings",
}

# Items that stay with the theme: its own settings, and the theme-scoped
# block placements (`theme:` key) which cannot be site config.
THEME_OWNED = re.compile(r"^(civictheme\.settings|block\.block\..*)$")


def classify(name: str, storages_by_recipe: dict) -> str:
    """Maps a config name to its owning recipe, 'theme' or 'action'."""
    if THEME_OWNED.match(name):
        return "theme"
    if name in ACTIONS:
        return "action:" + ACTIONS[name]
    if name in DROPPED:
        return "drop"

    rules = [
        (r"^(filter\.format|editor\.editor)\.", "civictheme_text_formats"),
        (r"^linkit\.linkit_profile\.", "civictheme_text_formats"),
        (r"^core\.date_format\.", "civictheme_date_formats"),
        (r"^taxonomy\.vocabulary\.", "civictheme_taxonomy"),
        (r"^(field\.storage|field\.field)\.taxonomy_term\.", "civictheme_taxonomy"),
        (r"^core\.entity_(form|view)_display\.taxonomy_term\.", "civictheme_taxonomy"),
        (r"^image\.style\.", "civictheme_image_styles"),
        (r"^responsive_image\.styles\.", "civictheme_image_styles"),
        (r"^media\.type\.", "civictheme_media"),
        (r"^(field\.storage|field\.field)\.media\.", "civictheme_media"),
        (r"^core\.entity_(form|view)_display\.media\.", "civictheme_media"),
        (r"^core\.entity_(form|view)_mode\.media\.", "civictheme_media"),
        (r"^views\.view\.civictheme_media$", "civictheme_media"),
        (r"^system\.menu\.", "civictheme_menus"),
        (r"^node\.type\.", "civictheme_node_types"),
        (r"^core\.base_field_override\.node\.", "civictheme_node_types"),
        (r"^core\.entity_view_mode\.node\.", "civictheme_node_types"),
        (r"^core\.entity_form_mode\.node\.", "civictheme_node_types"),
        # The webform paragraph (and its field storage, whose webform field
        # type needs the webform module) rides with the webform recipe.
        (r"^paragraphs\.paragraphs_type\.civictheme_webform$", "civictheme_webform"),
        (r"^field\.storage\.paragraph\.field_c_p_webform$", "civictheme_webform"),
        (r"^field\.field\.paragraph\.civictheme_webform\.", "civictheme_webform"),
        (
            r"^core\.entity_(form|view)_display\.paragraph\.civictheme_webform\.",
            "civictheme_webform",
        ),
        (r"^webform\.webform\.", "civictheme_webform"),
        # The banner form display places the content-moderation state widget,
        # making it the one block_content item depending on the editorial
        # workflow — which itself depends on the block_content types. It ships
        # with the workflow recipe to keep the recipe graph acyclic.
        (
            r"^core\.entity_form_display\.block_content\.civictheme_banner\.default$",
            "civictheme_editorial_workflow",
        ),
        (r"^paragraphs\.paragraphs_type\.", "civictheme_paragraphs"),
        (r"^(field\.storage|field\.field)\.paragraph\.", "civictheme_paragraphs"),
        (r"^core\.entity_(form|view)_display\.paragraph\.", "civictheme_paragraphs"),
        (r"^core\.entity_(form|view)_mode\.paragraph\.", "civictheme_paragraphs"),
        (r"^views\.view\.civictheme_automated_list$", "civictheme_paragraphs"),
        (r"^field\.field\.node\.civictheme_page\.", "civictheme_page"),
        (r"^core\.entity_(form|view)_display\.node\.civictheme_page\.", "civictheme_page"),
        (r"^pathauto\.pattern\.civictheme_page", "civictheme_page"),
        (r"^field\.field\.node\.civictheme_event\.", "civictheme_event"),
        (r"^core\.entity_(form|view)_display\.node\.civictheme_event\.", "civictheme_event"),
        (r"^pathauto\.pattern\.civictheme_event", "civictheme_event"),
        (r"^field\.field\.node\.civictheme_alert\.", "civictheme_alert"),
        (r"^core\.entity_(form|view)_display\.node\.civictheme_alert\.", "civictheme_alert"),
        (r"^pathauto\.pattern\.civictheme_alert", "civictheme_alert"),
        (r"^views\.view\.civictheme_alerts$", "civictheme_alert"),
        (r"^block_content\.type\.", "civictheme_block_types"),
        (r"^(field\.storage|field\.field)\.block_content\.", "civictheme_block_types"),
        (r"^core\.entity_(form|view)_display\.block_content\.", "civictheme_block_types"),
        (r"^core\.entity_(form|view)_mode\.block_content\.", "civictheme_block_types"),
        (r"^workflows\.workflow\.", "civictheme_editorial_workflow"),
        (r"^views\.view\.civictheme_moderated_content$", "civictheme_editorial_workflow"),
        (r"^user\.role\.", "civictheme_roles"),
        (r"^search_api\.(server|index)\.", "civictheme_search"),
        (r"^views\.view\.civictheme_search$", "civictheme_search"),
    ]
    for pattern, recipe in rules:
        if re.match(pattern, name):
            return recipe

    # Shared node field storages: owned by the single bundle recipe that uses
    # them, or by civictheme_node_types when several bundles share them.
    match = re.match(r"^field\.storage\.node\.(.+)$", name)
    if match:
        users = storages_by_recipe.get(match.group(1), set())
        if len(users) == 1:
            return next(iter(users))
        return "civictheme_node_types"

    return "UNMAPPED"


def load_config(theme_dir: Path) -> dict:
    """Load theme-owned config and the config shipped inside the recipes.

    Each item records its current "location": "theme" for config/install and
    config/optional, or the recipe directory name for recipes/*/config. The
    validation in main() asserts the location matches the classification.
    """
    items = {}
    for subdir in ("install", "optional"):
        directory = theme_dir / "config" / subdir
        if not directory.is_dir():
            continue
        for file in sorted(directory.glob("*.yml")):
            with open(file, encoding="utf-8") as handle:
                data = yaml.safe_load(handle)
            items[file.stem] = {
                "file": file,
                "location": "theme",
                "dependencies": (data or {}).get("dependencies", {}) or {},
            }
    for file in sorted(theme_dir.glob("recipes/*/config/*.yml")):
        with open(file, encoding="utf-8") as handle:
            data = yaml.safe_load(handle)
        items[file.stem] = {
            "file": file,
            "location": file.parent.parent.name,
            "dependencies": (data or {}).get("dependencies", {}) or {},
        }
    return items


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "--manifest",
        action="store_true",
        help="write .devtools/recipe-manifest.json",
    )
    args = parser.parse_args()

    items = load_config(THEME_DIR)

    # Pre-pass: which node bundles (hence recipes) use each node field storage.
    storages_by_recipe = defaultdict(set)
    for name in items:
        match = re.match(r"^field\.field\.node\.(civictheme_[a-z_]+)\.(.+)$", name)
        if match:
            bundle_recipe = "civictheme_" + match.group(1).removeprefix("civictheme_")
            storages_by_recipe[match.group(2)].add(bundle_recipe)

    assignment = {}
    for name in items:
        assignment[name] = classify(name, storages_by_recipe)

    # Each file must sit where its classification says it belongs: dropped and
    # action-sourced items ship no file at all.
    location_violations = []
    for name, item in items.items():
        recipe = assignment[name]
        if recipe in ("drop",) or recipe.startswith("action:"):
            location_violations.append(
                f"{name}: classified '{recipe}' but a file exists at {item['file']}"
            )
        elif recipe == "theme" and item["location"] != "theme":
            location_violations.append(
                f"{name}: theme-owned but sits in recipe '{item['location']}'"
            )
        elif recipe not in ("theme", "UNMAPPED") and item["location"] != recipe:
            location_violations.append(
                f"{name}: belongs to '{recipe}' but sits in '{item['location']}'"
            )

    unmapped = sorted(n for n, r in assignment.items() if r == "UNMAPPED")
    if unmapped:
        print("UNMAPPED config items:")
        for name in unmapped:
            print(f"  {name}")

    by_recipe = defaultdict(list)
    for name, recipe in sorted(assignment.items()):
        by_recipe[recipe].append(name)

    print("\n=== Assignment ===")
    for recipe in sorted(by_recipe):
        print(f"{recipe}: {len(by_recipe[recipe])}")

    # Transitive closure of the declared composition.
    def closure(recipe: str) -> set:
        seen = set()
        stack = list(RECIPE_COMPOSES.get(recipe, []))
        while stack:
            dep = stack.pop()
            if dep not in seen:
                seen.add(dep)
                stack.extend(RECIPE_COMPOSES.get(dep, []))
        return seen

    # Recipe-level edges from config dependencies, and modules needed.
    violations = []
    modules_needed = defaultdict(set)
    edges = defaultdict(set)
    external_config = defaultdict(set)
    for name, item in items.items():
        recipe = assignment[name]
        if recipe in ("theme", "UNMAPPED", "drop") or recipe.startswith("action:"):
            continue
        deps = item["dependencies"]
        for module in deps.get("module", []):
            modules_needed[recipe].add(module)
        prefix = ".".join(name.split(".")[:2])
        provider = PROVIDERS.get(prefix)
        if provider:
            modules_needed[recipe].add(provider)
        else:
            violations.append(f"{recipe}: no provider mapping for '{name}'")
        for target in deps.get("config", []):
            if target in assignment:
                target_recipe = assignment[target]
                if target_recipe.startswith("action:"):
                    target_recipe = target_recipe.split(":", 1)[1]
                if target_recipe not in (recipe, "theme"):
                    edges[recipe].add(target_recipe)
                    if target_recipe not in closure(recipe):
                        violations.append(
                            f"{recipe}: '{name}' needs '{target}' from "
                            f"{target_recipe}, which is not in its declared "
                            f"recipes chain"
                        )
                if target_recipe == "theme":
                    violations.append(
                        f"{recipe}: '{name}' depends on theme-owned '{target}'"
                    )
            else:
                external_config[recipe].add(target)

    print("\n=== Recipe-level dependency edges (from config) ===")
    for recipe in sorted(edges):
        print(f"{recipe} -> {', '.join(sorted(edges[recipe]))}")

    # Cycle check over the DECLARED composition.
    state = {}

    def visit(recipe: str, path: list) -> None:
        if state.get(recipe) == "done":
            return
        if state.get(recipe) == "visiting":
            violations.append("cycle: " + " -> ".join(path + [recipe]))
            return
        state[recipe] = "visiting"
        for dep in RECIPE_COMPOSES.get(recipe, []):
            visit(dep, path + [recipe])
        state[recipe] = "done"

    for recipe in RECIPE_COMPOSES:
        visit(recipe, [])

    # The site composition must apply every recipe after all its dependencies.
    seen = set()
    for recipe in SITE_RECIPE:
        missing = closure(recipe) - seen
        if missing:
            violations.append(
                f"civictheme_site: {recipe} applied before {sorted(missing)}"
            )
        seen.add(recipe)

    # Modules needed by each recipe's config must come from its own install
    # list or a composed recipe's.
    print("\n=== Module coverage ===")
    for recipe in sorted(modules_needed):
        available = set(RECIPE_INSTALL.get(recipe, []))
        for dep in closure(recipe):
            available.update(RECIPE_INSTALL.get(dep, []))
        available.update(ALWAYS_AVAILABLE)
        missing = modules_needed[recipe] - available
        if missing:
            violations.append(
                f"{recipe}: config needs modules not installed by its chain: "
                f"{sorted(missing)}"
            )
        print(f"{recipe}: needs {sorted(modules_needed[recipe])}")

    if external_config:
        print("\n=== External config dependencies (not shipped by the theme) ===")
        for recipe in sorted(external_config):
            print(f"{recipe}: {sorted(external_config[recipe])}")

    violations.extend(location_violations)

    if violations:
        print("\n=== VIOLATIONS ===")
        for violation in violations:
            print(f"  {violation}")

    if args.manifest:
        manifest = {
            "description": (
                "Maps every config item of the CivicTheme content model to the "
                "recipe that ships it. 'theme' marks items staying with the "
                "theme; 'action:<recipe>' marks simple config applied as a "
                "config action by that recipe; 'drop' marks config identical "
                "to module defaults that is not shipped at all. Regenerate "
                "with .devtools/recipe-map.py --manifest."
            ),
            "config": dict(sorted(assignment.items())),
        }
        out = THEME_DIR / ".devtools" / "recipe-manifest.json"
        with open(out, "w", encoding="utf-8") as handle:
            json.dump(manifest, handle, indent=2)
            handle.write("\n")
        print(f"\nManifest written: {out}")

    return 1 if violations or unmapped else 0


if __name__ == "__main__":
    sys.exit(main())
