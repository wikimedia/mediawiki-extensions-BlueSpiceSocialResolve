/*
* @author     Stefan Kühn
* @package    BluespiceSocial
* @subpackage BlueSpiceSocial
* @copyright  Copyright (C) 2020 Hallo Welt! GmbH, All rights reserved.
* @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
*/

bs.social = bs.social || {};
bs.social.EntityActionMenuResolved = bs.social.EntityActionMenu || {};

bs.social.EntityActionMenuResolved.Resolved = function ( entityActionMenu, data ) {
	OO.EventEmitter.call( this );
	var me = this;
	me.data = data || {};
	me.entityActionMenu = entityActionMenu;
	me.$element = null;

	if( me.data.resolved ) {
		me.$element = $( '<li><a class="dropdown-item">'
		+ mw.message( 'bs-socialresolve-status-notresolved' ).text()
		+ '</a></li>' );
	}
	else{
		me.$element = $( '<li><a class="dropdown-item">'
		+ mw.message( 'bs-socialresolve-status-resolved' ).text()
		+ '</a></li>' );
	}

	me.$element.on( 'click', function( e ) { me.click( e ); } );
	me.priority = 30;
};

OO.initClass( bs.social.EntityActionMenuResolved.Resolved );
OO.mixinClass( bs.social.EntityActionMenuResolved.Resolved, OO.EventEmitter );

bs.social.EntityActionMenuResolved.Resolved.prototype.click = function (e) {
	var me = this;
	e.stopPropagation();
	me.entityActionMenu.entity.showLoadMask();
	bs.api.tasks.execSilent(
		'socialresolve',
		'resolve',
		{ id: me.entityActionMenu.entity.id
			, type: me.entityActionMenu.entity.type
			, resolved: !me.data.resolved
		}
	).done( function( ) {
		me.entityActionMenu.entity.reload();
	});

	e.preventDefault();
	return false;
};
