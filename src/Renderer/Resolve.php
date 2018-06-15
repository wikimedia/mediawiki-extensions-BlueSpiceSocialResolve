<?php
namespace BlueSpice\Social\Resolve\Renderer;
use MediaWiki\Linker\LinkRenderer;
use BlueSpice\Renderer\Params;
use BlueSpice\Social\Resolve\Item;

class Resolve extends \BlueSpice\Renderer {
	const PARAM_RESOLVE_ITEM = 'resolveitem';
	const PARAM_USER = 'user';

	/**
	 *
	 * @var Item
	 */
	protected $item = null;

	/**
	 *
	 * @var User
	 */
	protected $user = null;

	/**
	 *
	 * @var Params
	 */
	protected $params = null;

	/**
	 *
	 * @param \Config $config
	 * @param Params $params
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct( \Config $config, Params $params, LinkRenderer $linkRenderer = null ) {
		parent::__construct( $config, $params, $linkRenderer );
		$this->item = $params->get(
			static::PARAM_RESOLVE_ITEM,
			false
		);
		if( !$this->item instanceof Item ) {
			throw new \MWException(
				'\\BlueSpice\\Social\\Resolve\\Item required'
			);
		}
		$this->user = $params->get(
			static::PARAM_USER,
			false
		);
		if( !$this->user instanceof \User ) {
			throw new \MWException( '\\User required' );
		}
		$this->args[static::PARAM_TAG] = 'a';
		if( !$this->args[static::PARAM_CLASS] ) {
			$this->args[static::PARAM_CLASS] = '';
		}
		$this->args[static::PARAM_CLASS] .= ' bs-social-entityaftercontent-resolve';
		if( $this->item->isResolved() ) {
			$this->args[static::PARAM_CLASS] .= ' resolved';
		}
	}

	/**
	 *
	 * @return string - HTML
	 */
	public function render() {
		$content = '';
		$content .= $this->getOpenTag();
		$content .= $this->makeTagContent();
		$content .= $this->getCloseTag();

		return $content;
	}

	protected function makeTagContent() {
		return \Message::newFromKey(
			$this->item->isResolved()
			? 'bs-socialresolve-status-resolved'
			: 'bs-socialresolve-status-notresolved'
		)->plain();
	}

	protected function makeTagAttribs() {
		$attrbs = [];
		$attrbs['data-resolve'] = \FormatJson::encode( $this->item );
		return array_merge( $attrbs, parent::makeTagAttribs() );
	}

	/**
	 *
	 * @return \User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * 
	 * @return array
	 */
	public function getArgs() {
		return $this->args;
	}
}
