<?php
namespace Smart\Blog\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '2.0.4', '<')) {
            // post_table
            if (!$installer->tableExists('smart_blog_post')) {

                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_blog_post')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Id'
                    )
                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                        ['nullable => false'],
                        'Name')
                    ->addColumn(
                        'url',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Url'
                    )
                    ->addColumn(
                        'description',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Description'
                    )
                    ->addColumn(
                        'content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Content'
                    )
                    ->addColumn(
                        'tags',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Tags'
                    )
                    ->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Status'
                    )
                    ->addColumn(
                        'thumbnail',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Thumbnail'
                    )
                    ->addColumn(
                        'gallery',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Gallery'
                    )
                    ->setComment('Post Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('smart_blog_post'),
                    $setup->getIdxName(
                        $installer->getTable('smart_blog_post'),
                        ['name', 'url', 'content', 'tags', 'thumbnail'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name', 'url', 'content', 'tags', 'thumbnail'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
            // category_table
            if (!$installer->tableExists('smart_blog_category')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_blog_category')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Id'
                    )
                    ->addColumn(
                        'parent_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        20,
                        [],
                        'Parent Id'
                    )
                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Name'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created_at'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated_at')
                    ->setComment('Categories Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('smart_blog_category'),
                    $setup->getIdxName(
                        $installer->getTable('smart_blog_category'),
                        ['name'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
            // post_category_table
            if (!$installer->tableExists('smart_blog_category_post')) {

                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_blog_category_post')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Category Post ID'
                    )
                    ->addColumn(
                        'post_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'Post ID'
                    )
                    ->addColumn(
                        'category_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'Category ID'
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_blog_category_post',
                            'post_id',
                            'smart_blog_post',
                            'id'
                        ),
                        'post_id',
                        $installer->getTable('smart_blog_post'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_blog_category_post',
                            'category_id',
                            'smart_blog_category',
                            'id'
                        ),
                        'category_id',
                        $installer->getTable('smart_blog_category'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment('Categories Posts Table');
                $installer->getConnection()->createTable($table);

            }
            // tag_table
            if (!$installer->tableExists('smart_blog_tag')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_blog_tag')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Id'
                    )

                    ->addColumn(
                        'name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Name'
                    )
                    ->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Created_at'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated_at')
                    ->setComment('Tag Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('smart_blog_tag'),
                    $setup->getIdxName(
                        $installer->getTable('smart_blog_tag'),
                        ['name'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
            // post_tag_table
            if (!$installer->tableExists('smart_blog_tag_post')) {

                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_blog_tag_post')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Tag Post ID'
                    )
                    ->addColumn(
                        'post_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'Post ID'
                    )
                    ->addColumn(
                        'tag_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => true,
                            'unsigned' => true,
                        ],
                        'Tag ID'
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_blog_tag_post',
                            'post_id',
                            'smart_blog_post',
                            'id'
                        ),
                        'post_id',
                        $installer->getTable('smart_blog_post'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_blog_tag_post',
                            'tag_id',
                            'smart_blog_tag',
                            'id'
                        ),
                        'tag_id',
                        $installer->getTable('smart_blog_tag'),
                        'id',
                        Table::ACTION_CASCADE
                    )
                    ->setComment('Tag Posts Table');
                $installer->getConnection()->createTable($table);

            }
        }

        // add colum in table smart_blog_post
        if (version_compare($context->getVersion(), '2.0.4', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('smart_blog_post'),
                'created_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => 255,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'comment' => 'Created_at'
                ]

            );
            $installer->getConnection()->addColumn(
                $installer->getTable('smart_blog_post'),
                'updated_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => 255,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'comment' => 'Updated_at'
                ]

            );
            $installer->getConnection()->addColumn(
                $installer->getTable('smart_blog_post'),
                'publish_date_from',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => 255,
                    [],
                    'comment' => 'Publish_date_from'
                ]

            );

            $installer->getConnection()->addColumn(
                $installer->getTable('smart_blog_post'),
                'publish_date_to',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => 255,
                    [],
                    'comment' => 'Publish_date_to'
                ]
            );
        }

        $installer->endSetup();
    }
}