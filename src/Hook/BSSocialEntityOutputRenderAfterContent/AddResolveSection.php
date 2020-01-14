<?php

namespace BlueSpice\Social\Resolve\Hook\BSSocialEntityOutputRenderAfterContent;

use BlueSpice\Social\Entity;
use BlueSpice\Social\Hook\BSSocialEntityOutputRenderAfterContent;

class AddResolveSection extends BSSocialEntityOutputRenderAfterContent {

	protected function doProcess() {
		$entity = $this->oEntityOutput->getEntity();

		if ( !$entity instanceof Entity ) {
			return true;
		}
		if ( !$entity->getConfig()->get( 'IsResolvable' ) ) {
			return true;
		}
		if ( !$entity->exists() ) {
			return true;
		}
		$factory = $this->getServices()->getService( 'BSSocialResolveFactory' );
		$resolveItem = $factory->newFromEntity( $entity );

		$renderer = $resolveItem->getRenderer(
			$this->getContext()->getUser()
		);
		$this->aViews[] = $renderer->render();
		return true;
	}
}
