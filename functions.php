<?php

// Sanitize output
function clean($data)
{
    return htmlspecialchars($data);
}

// Validate title
function validateTask($title)
{
    if(empty(trim($title)))
    {
        return false;
    }

    return true;
}
?>