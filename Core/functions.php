<?php

function dd($data) {
  echo '<pre>';
  die(var_dump($data));
  echo '</pre>';
}

function base_path($path)
{
    return BASE_PATH . $path;
}