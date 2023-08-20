Photo Organizer
===============

This tool takes a directory of photos and sorts them.

- It sorts all RAW photos in a subdirectory `_RAW` and leaves all other files in the root directory.
- It also renames all files to `YYYY-MM-DD HH-MM-SS - filename.ext`.

This makes it easy to merge files from different cameras in a single list.


Installation
------------

Install it via composer:

```bash
composer -g install apfelbox/photo-organizer
```

You also need to install [`exiftool`](https://github.com/exiftool/exiftool) and have it in the PATH.


Usage
-----

Add your global composer binaries to the PATH and call them:

```bash
photos organize
photos clean-raws
```


Commands
--------

### `organize`

Organizes all photos in the structure described above.


### `clean-raws`

Finds all RAW photos without and exported file and offers to move it to the trash.


Trash
-----

When any file is moved to the trash, that is a subdirectory in the current directory called `_TRASH`.
This directory is excluded from all other commands.


RAW
---

Currently only `.RAF` files are detected as RAWs, however that list is easily extendable in `PhotoFactory::RAW_FILE_EXTENSIONS`.
