<script>
    $(document).ready(function() {
        $('#editControls-<?php echo $id ?> a').click(function(e) {
            e.preventDefault();
            switch ($(this).data('role')) {
                case 'h1':
                case 'h2':
                case 'h3':
                case 'h4':
                case 'h5':
                case 'p':
                    document.execCommand('formatBlock', false, $(this).data('role'));
                    break;
                default:
                    document.execCommand($(this).data('role'), false, null);
                    break;
            }

            let textval = $("#editor-<?php echo $id ?>").html();
            $("#editorCopy-<?php echo $id ?>").val(textval);
        });

        $("#editor-<?php echo $id ?>").keyup(function() {
            let value = $(this).html();
            $("#editorCopy-<?php echo $id ?>").val(value);
        }).keyup();

        $('#checkIt').click(function(e) {
            e.preventDefault();
            alert($("#editorCopy-<?php echo $id ?>").val());
        });
    });
</script>