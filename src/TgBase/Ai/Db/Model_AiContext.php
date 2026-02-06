<?php

use RedBeanPHP\R;
use RedBeanPHP\SimpleModel;

class Model_AiContext extends SimpleModel
{
    /**
     * Вызывается автоматически перед сохранением (и при создании, и при обновлении)
     */
    public function update()
    {
        $now = date('Y-m-d H:i:s');

        if (!$this->bean->id) {
            $this->bean->created_at = $now;
        }

        $this->bean->updated_at = $now;
    }

    public function getDecodedContext(): array
    {
        return json_decode($this->bean->context, true) ?? [];
    }
}