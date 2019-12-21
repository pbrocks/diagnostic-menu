var data = {
    'action': 'cvf_td_veiw_frontend_message',
    'cvf_action': 'veiw_frontend_message'
};

$.post(global.ajax_url, data, function(response) {
    
    // After the response has been appended to the our front-end... 
    if($(".cvf_td_conversation_messages").html(response)){
        
        // Get the contents of the hidden wp_editor
        reply_editor = $('.hidden-editor-container').contents();
        
        // Append the contents of the hidden wp_editor to div container
        $('.hidden-reply-editor').append( reply_editor );
        
        // Reinitialize the editor: Remove the editor then add it back
        tinymce.execCommand( 'mceRemoveEditor', false, 'tdmessagereply' );
        tinymce.execCommand( 'mceAddEditor', false, 'tdmessagereply' );
    }
});