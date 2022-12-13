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

namespace Civi\Api4;

use Civi\Api4\Generic\AbstractEntity;
use Civi\Api4\Generic\BasicGetFieldsAction;
use Civi\Api4\Action\BirthdayGreeting\Send;

final class BirthdayGreeting extends AbstractEntity {

    public static function send($checkPermissions = true): Send {
        return (new Send(__CLASS__,__FUNCTION__))->setCheckPermissions($checkPermissions);
    }

    public static function getFields($checkPermissions = true) {
        return (new BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
            return [];
        }))->setCheckPermissions($checkPermissions);
    }

}
