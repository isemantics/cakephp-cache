<?php
namespace Cache\Test\TestCase\Shell;

use Cache\View\PartialCacheView;
use Tools\TestSuite\TestCase;

/**
 */
class PartialCacheViewTest extends TestCase {

	/**
	 * @var \Cache\View\PartialCacheView|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $PartialCacheView;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->PartialCacheView = $this->getMockBuilder(PartialCacheView::class)
			->setMethods(['_getViewFileName'])
			->getMock();
		$this->testCacheFile = dirname(dirname(__DIR__)) . DS . 'test_files' . DS . 'partial' . DS . 'view.html';
		$this->tmpDir = CACHE . 'views' . DS;
		if (!is_dir($this->tmpDir)) {
			mkdir($this->tmpDir, 0770, true);
		}
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Shell);
	}

	/**
	 * @return void
	 */
	public function testRenderCacheMiss() {
		$this->PartialCacheView->autoLayout(false);

		$result = $this->PartialCacheView->render();

		$this->assertSame('', $result);
	}

	/**
	 * @return void
	 */
	public function testRenderCacheHit() {
		$this->PartialCacheView->expects($this->once())->method('_getViewFileName')->willReturn('view');
		$this->PartialCacheView->autoLayout(false);
		copy($this->testCacheFile, $this->tmpDir . 'view');

		$result = $this->PartialCacheView->render();

		$this->assertContains('<!--created:', $result);
		$this->assertContains('<p>Some paragraph.</p>', $result);
		$this->assertContains('<!--end-->', $result);
	}

}
