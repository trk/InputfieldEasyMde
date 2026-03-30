# ProcessWire Inputfield: EasyMDE

A native ProcessWire Inputfield module that instantly transforms any standard `<textarea>` into a robust, beautiful Markdown editor utilizing [EasyMDE](https://github.com/Ionaru/easy-markdown-editor).

## Overview

Unlike heavy WYSIWYG editors (like CKEditor or TinyMCE), **InputfieldEasyMde** focuses solely on Markdown (`.md`) formatting. It provides a distraction-free, syntax-highlighted editor with a clean toolbar (bold, italics, lists, links, image tags, etc.) perfect for writing LLM System Prompts, plain text articles, or developer documentation.

### Features
- **Local Assets:** No CDN dependency. `easymde.min.js` and `easymde.min.css` are bundled locally in the `/resources/assets/` folder ensuring absolute stability and privacy for your admin backend.
- **Dynamic DOM Awareness:** The module features an intelligent `MutationObserver`. If a new textarea is dynamically injected into the DOM (e.g., via AJAX, HTMX, or Hyperscript clones) and possesses the `.InputfieldEasyMdeInit` class, the module instantiates the editor instantly without requiring a page reload.
- **Form Sync:** EasyMDE is configured with `forceSync: true` by default, meaning hidden `<textarea>` values are always strictly synchronized with the editor's visual state payload so DOM serializers (like those in Alpine or Hyperscript) can grab data accurately before form submission.
- **Backend Configurable:** Fully configurable via ProcessWire's module/field settings. You can dynamically adjust spell checking, status bar visibility, min/max heights, and even provide a custom toolbar layout without typing any JavaScript.
- **Auto Garbage Collection:** Built-in vanilla JS mutation observers automatically detect when an editor instance is removed from the DOM and securely disposes of it, eliminating memory leaks in active Single Page Applications without any external library dependencies.

## Installation

### Via Composer (Recommended)
```bash
composer require trk/processwire-easymde
```
Login to your ProcessWire admin, go to **Modules > Refresh**, and click "Install" for **Inputfield EasyMDE**.

### Manual Installation
1. Copy module folder to `/site/modules/InputfieldEasyMde/`
2. Navigate to **Modules > Refresh** in your ProcessWire admin.
3. Click "Install" for **Inputfield EasyMDE**.

## Usage (For Developers)

To use the editor on your custom module or inputfield:

1. Ensure the module is loaded via `$modules->get('InputfieldEasyMde')->renderReady()`. This queues the necessary Scripts and Styles into the ProcessWire admin header.
2. Add the CSS class `InputfieldEasyMdeInit` to any standard `<textarea>` element in your markup.

```php
// Backend PHP Context
if (wire('modules')->isInstalled('InputfieldEasyMde')) {
    wire('modules')->get('InputfieldEasyMde')->renderReady();
}

$html = "<textarea name='my_prompt' class='InputfieldEasyMdeInit'></textarea>";
```

Because of the internal Javascript MutationObserver, you don't need to manually trigger any initialization bindings. As soon as the browser paints the `.InputfieldEasyMdeInit` textarea onto the DOM, it converts to the Markdown UI.
