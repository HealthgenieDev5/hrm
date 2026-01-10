<?php
function display_error($validation, $field)
{
    if ($validation->hasError($field)) {
        return $validation->getError($field);
    } else {
        return false;
    }
}

if (! function_exists('edit_set_select')) {
    function edit_set_select($field = '', $value = '', $preset_value = '')
    {
        if ($value == $preset_value) {
            return set_select($field, $preset_value, TRUE);
        } else {
            return set_select($field, $value);
        }
    }
}
