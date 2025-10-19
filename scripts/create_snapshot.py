#!/usr/bin/env python3
"""Create compressed working tree snapshots without touching the Git index."""

from __future__ import annotations

import argparse
import re
import sys
from datetime import datetime
from pathlib import Path
from typing import Iterable
import zipfile


SNAPSHOT_LIMIT = 5
SNAPSHOT_PREFIX = "snapshot-"
EXCLUDED_TOP_LEVEL = {".git", ".snapshots"}


def iter_repository_files(root: Path) -> Iterable[Path]:
    """Yield repository files excluding ignored top-level directories."""
    for path in root.rglob("*"):
        if not path.is_file():
            continue

        relative = path.relative_to(root)
        if relative.parts and relative.parts[0] in EXCLUDED_TOP_LEVEL:
            continue

        yield relative


def sanitize_label(label: str | None) -> str:
    """Return a safe suffix for the snapshot file name."""
    if not label:
        return ""

    cleaned = re.sub(r"\s+", "-", label.strip())
    cleaned = re.sub(r"[^A-Za-z0-9_.-]", "", cleaned)
    return f"-{cleaned}" if cleaned else ""


def create_snapshot(label: str | None) -> Path:
    """Create a zip snapshot of the working tree and enforce retention."""
    script_path = Path(__file__).resolve()
    repo_root = script_path.parent.parent
    snapshots_dir = repo_root / ".snapshots"
    snapshots_dir.mkdir(exist_ok=True)

    timestamp = datetime.now().strftime("%Y%m%d-%H%M%S")
    suffix = sanitize_label(label)

    archive_name = f"{SNAPSHOT_PREFIX}{timestamp}{suffix}.zip"
    archive_path = snapshots_dir / archive_name

    if archive_path.exists():
        raise FileExistsError(f"Snapshot {archive_path.name} already exists.")

    with zipfile.ZipFile(archive_path, mode="w", compression=zipfile.ZIP_DEFLATED) as archive:
        for relative in iter_repository_files(repo_root):
            archive.write(repo_root / relative, arcname=str(relative))

    enforce_retention(snapshots_dir)
    return archive_path


def enforce_retention(snapshots_dir: Path) -> None:
    """Keep only the most recent SNAPSHOT_LIMIT archives."""
    archives = sorted(
        snapshots_dir.glob(f"{SNAPSHOT_PREFIX}*.zip"),
        key=lambda path: path.stat().st_mtime,
        reverse=True,
    )

    for old_archive in archives[SNAPSHOT_LIMIT:]:
        old_archive.unlink(missing_ok=True)


def parse_args(argv: list[str]) -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Create a snapshot of the current working tree."
    )
    parser.add_argument(
        "-l",
        "--label",
        help="Optional label to append to the snapshot name (spaces will become dashes).",
    )
    return parser.parse_args(argv)


def main(argv: list[str] | None = None) -> int:
    args = parse_args(argv or sys.argv[1:])
    try:
        archive_path = create_snapshot(args.label)
    except Exception as exc:  # pylint: disable=broad-except
        print(f"[snapshot] Failed: {exc}", file=sys.stderr)
        return 1

    print(f"[snapshot] Created {archive_path}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
