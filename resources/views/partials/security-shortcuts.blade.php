<script id="swiftbite-security-shortcuts">
    (function () {
        const allowViewSource = @json($allowViewSource ?? false);
        const allowInspect = @json($allowInspect ?? false);
        const blockedKeys = new Set([
        ]);

        @unless($allowInspect ?? false)
            blockedKeys.add('F12');
        @endunless

        const blockedCtrlKeys = new Set([
            's', // Save page
            'p', // Print page
        ]);

        @unless($allowViewSource ?? false)
            blockedCtrlKeys.add('u'); // View source
        @endunless

        const blockedCtrlShiftKeys = new Set([]);

        @unless($allowInspect ?? false)
            blockedCtrlShiftKeys.add('i'); // DevTools
            blockedCtrlShiftKeys.add('j'); // Console
            blockedCtrlShiftKeys.add('c'); // Inspect element
            blockedCtrlShiftKeys.add('k'); // Firefox console
        @endunless

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

            if (!allowViewSource && hasCtrl && event.altKey && key === 'u') {
                return block(event);
            }

            if (hasCtrl && !event.shiftKey && blockedCtrlKeys.has(key)) {
                return block(event);
            }
        }, true);

        @unless($allowInspect ?? false)
            document.addEventListener('contextmenu', block, true);
        @endunless
    })();
</script>
