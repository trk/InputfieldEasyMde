<?php

namespace ProcessWire;

/**
 * EasyMDE Markdown Editor Inputfield
 * 
 * Provides a lightweight markdown editor for ProcessWire Textareas.
 */
class InputfieldEasyMde extends InputfieldTextarea
{

    public function __construct() {
        parent::__construct();
        $this->set('spellChecker', 0);
        $this->set('status', 0);
        $this->set('minHeight', '300px');
        $this->set('maxHeight', '');
        $this->set('toolbar', '');
    }

    public function renderReady(?Inputfield $parent = null, $renderValueMode = false)
    {
        $result = parent::renderReady($parent, $renderValueMode);

        $config = $this->wire('config');
        $url = $config->urls->InputfieldEasyMde;

        // Load local EasyMDE assets
        $config->styles->add($url . 'resources/assets/easymde.min.css');
        $config->scripts->add($url . 'resources/assets/easymde.min.js');

        return $result;
    }

    public function render() {
        $this->addClass('InputfieldEasyMdeInit');
        
        $config = [
            'spellChecker' => (bool) $this->spellChecker,
            'status' => (bool) $this->status,
        ];
        
        if ($this->minHeight) $config['minHeight'] = $this->minHeight;
        if ($this->maxHeight) $config['maxHeight'] = $this->maxHeight;
        
        if ($this->toolbar) {
            // Convert comma separated string to array for EasyMDE
            $config['toolbar'] = array_values(array_filter(array_map('trim', explode(',', $this->toolbar))));
        }
        
        $this->setAttribute('data-easymde-config', json_encode($config));
        
        return parent::render();
    }
    
    public function ___getConfigInputfields() {
        $inputfields = parent::___getConfigInputfields();
        
        /** @var InputfieldCheckbox $f */
        $f = $this->wire('modules')->get('InputfieldCheckbox');
        $f->attr('name', 'spellChecker');
        $f->label = $this->_('Enable Spell Checker');
        $f->attr('checked', $this->spellChecker ? 'checked' : '');
        $f->columnWidth = 50;
        $inputfields->add($f);
        
        /** @var InputfieldCheckbox $f */
        $f = $this->wire('modules')->get('InputfieldCheckbox');
        $f->attr('name', 'status');
        $f->label = $this->_('Show Status Bar');
        $f->attr('checked', $this->status ? 'checked' : '');
        $f->columnWidth = 50;
        $inputfields->add($f);
        
        /** @var InputfieldText $f */
        $f = $this->wire('modules')->get('InputfieldText');
        $f->attr('name', 'minHeight');
        $f->label = $this->_('Minimum Height');
        $f->description = $this->_('e.g., 300px');
        $f->attr('value', $this->minHeight);
        $f->columnWidth = 50;
        $inputfields->add($f);
        
        /** @var InputfieldText $f */
        $f = $this->wire('modules')->get('InputfieldText');
        $f->attr('name', 'maxHeight');
        $f->label = $this->_('Maximum Height');
        $f->description = $this->_('e.g., 500px, leave blank for auto');
        $f->attr('value', $this->maxHeight);
        $f->columnWidth = 50;
        $inputfields->add($f);

        /** @var InputfieldText $f */
        $f = $this->wire('modules')->get('InputfieldText');
        $f->attr('name', 'toolbar');
        $f->label = $this->_('Custom Toolbar');
        $f->description = $this->_('Comma-separated list of tools. e.g., bold, italic, heading, |, quote, unordered-list, ordered-list, |, link, image, |, preview, side-by-side, fullscreen. Leave blank for default.');
        $f->attr('value', $this->toolbar);
        $inputfields->add($f);

        return $inputfields;
    }
}
