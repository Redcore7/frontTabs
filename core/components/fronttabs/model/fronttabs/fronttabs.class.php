<?php

/**
 * The base class for frontTabs.
 */
class frontTabs {
	/* @var modX $modx */
	public $modx;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('fronttabs_core_path', $config, $this->modx->getOption('core_path') . 'components/fronttabs/');
		$assetsUrl = $this->modx->getOption('fronttabs_assets_url', $config, $this->modx->getOption('assets_url') . 'components/fronttabs/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/'
		), $config);

		$this->modx->addPackage('fronttabs', $this->config['modelPath']);
		$this->modx->lexicon->load('fronttabs:default');
	}

	/**
	 * Initializes component into different contexts.
	 *
	 * @param string $ctx The context to load. Defaults to web.
	 * @param array $scriptProperties Properties for initialization.
	 *
	 * @return bool
	 */
	public function initialize($ctx = 'web', $scriptProperties = array()) {
		$this->config = array_merge($this->config, $scriptProperties);
		$this->config['ctx'] = $ctx;
		if (!empty($this->initialized[$ctx])) {
			return true;
		}
		switch ($ctx) {
			case 'mgr': break;
			default:
				if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
					$config = $this->makePlaceholders($this->config);
					if ($css = $this->modx->getOption('ft_frontend_css')) {
						$this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
					}
					$this->config['rememberTab'] = $this->config['rememberTab'] ? $this->config['rememberTab'] : 0;
					$config_js = preg_replace(array('/^\n/', '/\t{5}/'), '', '
					frontTabsConfig = {
						rememberTab: ' . $this->config['rememberTab'] . ',
						activeClass: "' . $this->config['activeClass'] . '"
					};');
					$this->modx->regClientStartupScript("<script type=\"text/javascript\">\n".$config_js."\n</script>", true);

					if ($js = trim($this->modx->getOption('ft_frontend_js'))) {
						if (!empty($js) && preg_match('/\.js/i', $js)) {
							$this->modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
							<script type="text/javascript">
								if(typeof jQuery == "undefined") {
									document.write("<script src=\"'.$this->config['jsUrl'].'web/lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
								}
							</script>
							'), true);
							$this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
						}
					}
				}

				$this->initialized[$ctx] = true;
				break;
		}
		return true;
	}


	/**
	 * Method for transform array to placeholders
	 *
	 * @var array $array With keys and values
	 * @var string $prefix Placeholders prefix
	 *
	 * @return array $array Two nested arrays With placeholders and values
	 */
	public function makePlaceholders(array $array = array(), $prefix = '') {
		$result = array('pl' => array(), 'vl' => array());
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$result = array_merge_recursive($result, $this->makePlaceholders($v, $k.'.'));
			}
			else {
				$result['pl'][$prefix.$k] = '[[+'.$prefix.$k.']]';
				$result['vl'][$prefix.$k] = $v;
			}
		}
		return $result;
	}
}