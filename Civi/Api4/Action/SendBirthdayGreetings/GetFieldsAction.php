<?php
/*
 * Copyright (C) 2022 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types = 1);

namespace Civi\Api4\Action\SendBirthdayGreetings;

use Civi\Api4\SendBirthdayGreetings;
use Civi\Api4\Generic\AbstractGetAction;
use Civi\Api4\Generic\Result;

class GetFieldsAction extends AbstractGetAction {
    public function fields() {
        return [
            [
                'name' => 'id',
                'data_type' => 'Integer',
                'description' => 'Unique identifier. If it were named something other than "id" we would need to override the getInfo() function to supply "primary_key".',
            ],
            [
                'name' => 'example_str',
                'description' => "Example string field. We don't need to specify data_type as String is the default.",
            ],
            [
                'name' => 'example_int',
                'data_type' => 'Integer',
                'description' => "Example number field. The Api Explorer will present this as numeric input.",
            ],
            [
                'name' => 'example_bool',
                'data_type' => 'Boolean',
                'description' => "Example boolean field. The Api Explorer will present true/false options.",
            ],
            [
                'name' => 'example_options',
                'description' => "Example field with option list. The Api Explorer will display these options.",
                'options' => ['r' => 'Red', 'b' => 'Blue', 'g' => 'Green'],
            ],
        ];
    }
}
