<?php
/**
 * Item class for extension Resolve
 *
 * Provides a rating item.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit http://bluespice.com
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BlueSpiceSocial
 * @subpackage BlueSpiceSocialResolve
 * @copyright  Copyright (C) 2017 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v2 or later
 * @filesource
 */
namespace BlueSpice\Social\Resolve;

use BlueSpice\Social\Entity;
use BlueSpice\Services;
use BlueSpice\Renderer\Params;
use BlueSpice\Social\Resolve\Renderer\Resolve;

class Item implements \JsonSerializable{

	/**
	 *
	 * @var Entity
	 */
	protected $entity = null;

	/**
	 *
	 * @var boolean
	 */
	protected $resolved = false;

	public function __construct( Entity $entity ) {
		$this->entity = $entity;
		$this->resolved = $entity->get( 'resolved', false );
	}

	public function jsonSerialize() {
		$data = [];
		$oStatus = $this->getEntity()->userCan(
			'resolve',
			\RequestContext::getMain()->getUser()
		);
		$data['usercanresolve'] = $oStatus->isOK();
		$data['id'] = $this->getEntity()->get( Entity::ATTR_ID, 0 );
		$data['resolved'] = $this->isResolved();
		return $data;
	}

	/**
	 *
	 * @return Entity;
	 */
	public function getEntity() {
		return $this->entity;
	}

	/**
	 *
	 * @return \Config
	 */
	public function getConfig() {
		return $this->getEntity()->getConfig();
	}

	/**
	 *
	 * @return boolean
	 */
	public function isResolved() {
		return $this->resolved ? true : false;
	}

	public function resolve( \User $user, $value ) {
		if( !$this->getEntity() instanceof Entity ) {
			return \Status::newFatal( 'invalid Item' );//TODO:: msg
		}
		$value = $value ? true : false;
		$status = $this->getEntity()->userCan( 'resolve', $user );
		if( !$status->isOK() ) {
			return $status;
		}

		$this->getEntity()->set( 'resolved', $value );
		$status = $this->getEntity()->save( $user );
		if( !$status->isOK() ) {
			return $status;
		}

		return \Status::newGood( $this );
	}

	public function getRenderer( \User $user = null ) {
		if( !$user ) {
			$user = \RequestContext::getMain()->getUser();
		}
		return Services::getInstance()->getBSRendererFactory()->get(
			'entityresolve',
			new Params( [
				Resolve::PARAM_RESOLVE_ITEM => $this,
				Resolve::PARAM_USER => $user
			])
		);
	}
}