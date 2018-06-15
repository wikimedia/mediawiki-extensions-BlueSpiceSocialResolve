<?php

namespace BlueSpice\Social\Resolve\Hook\BSSocialAllowedSorterValues;
use BlueSpice\Social\Hook\BSSocialAllowedSorterValues;

class AddResolve extends BSSocialAllowedSorterValues {
	protected function doProcess() {
		$this->aSorters[] = 'resolved';
		return true;
	}
}
