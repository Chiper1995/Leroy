<?php
/**
 *
 * full preset returns the full toolbar configuration set for CKEditor.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */
return [
    'height' => 400,
    'toolbarGroups' => [
        ['name' => 'tools', 'groups' => ['tools']],
        ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
        ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
        ['name' => 'editing', 'groups' => ['find', 'selection', 'editing']],
        ['name' => 'forms', 'groups' => ['forms']],
        ['name' => 'styles', 'groups' => ['styles']],
        '/',
        ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
        ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']],
        ['name' => 'links', 'groups' => ['links']],
        ['name' => 'insert', 'groups' => ['insert']],
        ['name' => 'colors', 'groups' => ['colors']],
        ['name' => 'others', 'groups' => ['others']],
        ['name' => 'about', 'groups' => ['about']]
    ],
    'removeButtons' => 'Source,Save,Templates,NewPage,Preview,Print,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,Language,Image,Flash,HorizontalRule,PageBreak,Iframe,About,Styles,Format,Font,FontSize,ShowBlocks,CreateDiv',
];

