/**
 *
 * @author     Patric Wirth <wirth@hallowelt.com>
 * @package    BluespiceSocial
 * @subpackage BlueSpiceSocialResolve
 * @copyright  Copyright (C) 2018 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 */
$( document ).bind( 'BSSocialEntityInit', function( event, Entity ) {
	if( !Entity.getConfig().IsResolvable ) {
		return;
	}
	if( Entity.hasParent() ) {
		return;
	}
	if( Entity.resolveItem ) {
		return;
	}
	var $lnk = Entity.getContainer( Entity.AFTER_CONTENT_CONTAINER )
			.find( '.bs-social-entityaftercontent-resolve' ).first();
	if( $lnk.length < 1 ) {
		return;
	}
	var data = $lnk.data( 'resolve' );
	if( !data || !data.usercanresolve ) {
		return;
	}

	$lnk.on( 'click', function( e ) {
		e.stopPropagation();
		Entity.showLoadMask();
		bs.api.tasks.execSilent(
			'socialresolve',
			'resolve',
			{ id: Entity.id, resolved: !data.resolved }
		).done( function( response ) {
			Entity.reload();
		});
		return false;
	});
});