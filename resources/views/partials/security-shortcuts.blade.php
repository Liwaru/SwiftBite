<script id="swiftbite-security-shortcuts">
    (function () {
        const blockedKeys = new Set([
            'F12',
        ]);

        const blockedCtrlKeys = new Set([
            'u', // View source
            's', // Save page
            'p', // Print page
        ]);

        const blockedCtrlShiftKeys = new Set([
            'i', // DevTools
            'j', // Console
            'c', // Inspect element
            'k', // Firefox console
        ]);

        function block(event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
            return false;
        }

        document.addEventListener('keydown', function (event) {
            const key = event.key.length === 1 ? event.key.toLowerCase() : event.key;
            const hasCtrl = event.ctrlKey || event.metaKey;

            if (blockedKeys.has(key)) {
                return block(event);
            }

            if (hasCtrl && event.shiftKey && blockedCtrlShiftKeys.has(key)) {
                return block(event);
            }

            if (hasCtrl && event.altKey && key === 'u') {
                return block(event);
            }

            if (hasCtrl && !event.shiftKey && blockedCtrlKeys.has(key)) {
                return block(event);
            }
        }, true);

        document.addEventListener('contextmenu', block, true);
    })();
</script>
