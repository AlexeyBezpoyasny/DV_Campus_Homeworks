<?php
declare(strict_types=1);

namespace Alexeyb\Chat\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Component\ComponentRegistrar;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Framework\File\Csv $csv
     */
    private $csv;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     */
    private $componentRegistrar;

    /**
     * UpgradeData constructor.
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     * @param \Magento\Framework\File\Csv $csv
     */
    public function __construct(
        \Magento\Framework\Component\ComponentRegistrar $componentRegistrar,
        \Magento\Framework\File\Csv $csv
    ) {
        $this->componentRegistrar = $componentRegistrar;
        $this->csv = $csv;
    }
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->installDemoData($setup, 'data.csv');
        }

        $setup->endSetup();
    }
    /**
     * @param ModuleDataSetupInterface $setup
     * @return void
     * @throws \Exception
     */
    private function installDemoData(ModuleDataSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $filePath = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Alexeyb_Chat')
            . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'data.csv';

        $tableName = $setup->getTable('alexeyb_chat');
        $csvData = $this->csv->getData($filePath);

        try {
            $connection->beginTransaction();
            $columns = [
                'author_type',
                'author_id',
                'author_name',
                'message_text',
                'chat_hash',
            ];

            foreach ($csvData as $rowNumber => $data) {
                $insertedData = array_combine($columns, $data);

                $setup->getConnection()->insertOnDuplicate(
                    $tableName,
                    $insertedData,
                    $columns
                );
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }
    }
}
