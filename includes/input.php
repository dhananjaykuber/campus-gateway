<?php

function customInput($type, $name, $placeholder="", $value="", $width="min-w-[300px]") {
    $input = "<input type='$type' name='$name' id='$name' placeholder='$placeholder' value='$value' autocomplete='false' class='flex w-full rounded-md bg-neutral-700 border border-transparent px-3 py-3 text-sm font-medium file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-neutral-400 disabled:cursor-not-allowed disabled:opacity-50 focus:outline-none $width'>";

    return $input;
}

?>