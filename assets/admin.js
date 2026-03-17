jQuery(document).ready(function($) {
    const DONE_TYPING_INTERVAL = 800;

    $('.wiih-note-field').on('input', function() {
        const $field = $(this);
        const $statusSpan = $field.next('span');

        $statusSpan.stop(true, true).show().text('Saving...').css('color', '#888');

        clearTimeout($field.data('typingTimer'));

        const timer = setTimeout(() => {
            saveNote($field, $field.data('plugin-id'), $statusSpan);
        }, DONE_TYPING_INTERVAL);

        $field.data('typingTimer', timer);
    });

    function saveNote($field, pluginId, $statusSpan) {
        const noteText = $field.val();

        if ($field.data('last-saved-val') === noteText) {
            $statusSpan.text('');
            return; 
        }

        $.ajax({
            url: wiihData.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_wiih_note',
                security: wiihData.security,
                plugin_id: pluginId,
                note: noteText
            },
            success: (response) => {
                if (response.success) {
                    $field.data('last-saved-val', noteText); 

                    $statusSpan.text('Saved!').css('color', '#00a32a');
                    
                    setTimeout(() => {
                        $statusSpan.fadeOut('slow', function() {
                            $(this).text('').show();
                        });
                    }, 2000);
                } else {
                    $statusSpan.text('Error saving.').css('color', '#d63638');
                }
            },
            error: () => {
                $statusSpan.text('Server error.').css('color', '#d63638');
            }
        });
    }
});