<?php

/**
 *
 * basic preset returns the basic toolbar configuration set for CKEditor.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 */
return [
    'height' => 100,
    'toolbarGroups' => [
        //['name' => 'undo'],
        ['name' => 'basicstyles', 'groups' => ['basicstyles', 'insert'/*, 'cleanup'*/]],
        //['name' => 'colors'],
        //['name' => 'links', 'groups' => ['links', 'insert']],
        //['name' => 'others', 'groups' => ['others', 'about']],
    ],
    'removeButtons' => 'Subscript,Superscript,Flash,Table,HorizontalRule,SpecialChar,PageBreak,Iframe,Image',
    'removePlugins' => 'elementspath',
    'resize_enabled' => false
];
