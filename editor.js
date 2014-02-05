addEventListener('DOMContentLoaded', function() {
   var editors = document.getElementsByClassName('html_editor');
   for(var i=0; i<editors.length; i++) {
       var editor = editors.item(i);
       if ('TEXTAREA' == editor.nodeName) {
           var old = editor;
           editor = document.createElement('div');
           old.parentNode.replaceChild(editor, old);
       }
       editor.setAttribute('contentEditable', 'true');

       editor.addEventListener('click', function(e) {
           var selection = getSelection();
           if ((selection.focusOffset - selection.anchorOffset))
               console.log(selection);
       })
   }
});