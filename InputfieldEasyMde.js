(function() {
    function initEasyMde(textarea) {
        // Prevent double initialization
        if (textarea.hasAttribute('data-easymde')) return;
        textarea.setAttribute('data-easymde', '1');
        var baseConfig = {
            element: textarea,
            forceSync: true, // Crucial for HTMX/Hyperscript serializers
            sideBySideFullscreen: false
        };
        
        var userConfigStr = textarea.getAttribute('data-easymde-config');
        if (userConfigStr) {
            try {
                var userConfig = JSON.parse(userConfigStr);
                baseConfig = Object.assign(baseConfig, userConfig);
            } catch (e) {
                console.error("Invalid EasyMDE JSON config: ", e);
            }
        }
        
        textarea._easymde = new EasyMDE(baseConfig);
    }

    function destroyEasyMde(textarea) {
        if (textarea._easymde) {
            textarea._easymde.toTextArea();
            delete textarea._easymde;
            textarea.removeAttribute('data-easymde');
        }
    }

    // Initialize on standard DOM load
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.InputfieldEasyMdeInit').forEach(initEasyMde);
        
        // Start observing only after body is ready
        if (document.body) {
            observer.observe(document.body, { childList: true, subtree: true });
        }
    });

    // Mutation Observer to handle elements dynamically injected or removed from DOM
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            
            // Garbage Collection (Memory Cleanup) on node removal
            if (mutation.removedNodes.length > 0) {
                mutation.removedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.classList && node.classList.contains('InputfieldEasyMdeInit')) {
                            destroyEasyMde(node);
                        }
                        if (node.querySelectorAll) {
                            node.querySelectorAll('.InputfieldEasyMdeInit').forEach(destroyEasyMde);
                        }
                    }
                });
            }

            // Initialization on node addition
            if (mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(function(node) {
                    // Ensure it's an Element node
                    if (node.nodeType === 1) {
                        if (node.classList && node.classList.contains('InputfieldEasyMdeInit')) {
                            initEasyMde(node);
                        }
                        if (node.querySelectorAll) {
                            node.querySelectorAll('.InputfieldEasyMdeInit').forEach(initEasyMde);
                        }
                    }
                });
            }
        });
    });
})();
