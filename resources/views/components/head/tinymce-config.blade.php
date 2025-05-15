<div>
    <!-- I have not failed. I've just found 10,000 ways that won't work. - Thomas Edison -->
</div>
<script src="https://cdn.tiny.cloud/1/gg7y2bco0b61kanxr5kqxqi6mncn262xluusx3aeyjs13r4z/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'code table lists link image media searchreplace visualblocks fullscreen help wordcount',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | link image',
    promotion: false,
    branding: false,
    height: 500,
    setup: function (editor) {
      editor.on('init', function () {
        // Handle the TinyMCE domain check error
        const notification = document.querySelector('.tox-notification--error');
        if (notification) {
          notification.style.display = 'none';
        }
      });
    },
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    // Add a unique origin parameter to help with domain verification
    document_base_url: window.location.origin,
    referrer_policy: 'origin'
  });
</script>