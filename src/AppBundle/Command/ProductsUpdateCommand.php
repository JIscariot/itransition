<?php

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use AppBundle\Extractor\ProductExtractor;
use AppBundle\Validator\ProductValidator;
use AppBundle\Validator\Validator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductsUpdateCommand extends ContainerAwareCommand
{
    protected $effected = [];
    protected $affected = [];

    protected function configure()
    {
        $this
            ->setName('products:update')
            ->setDescription('This command allows you update products list.')
            ->addArgument('test', InputArgument::OPTIONAL, 'Run command in test mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get csv file path.
        $file = $this->getContainer()->getParameter('storage.stock');
        // Get entity manager.
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        // Init Serializer.
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        // Decoding CSV contents.
        $items = $serializer->decode(file_get_contents($file), 'csv');

        foreach ($items as $item) {

            // Extract data from arr and validate.
            $item = new ProductExtractor($item);
            $validator = new ProductValidator($item);

            if (!$validator->isValid()) {
                $this->affected[] = "Product code: {$item->getCode()} not imported.";
                continue;
            }

            $repository = $em->getRepository(Product::class);
            // Get product fom database, if not exists try create.
            $product = $repository->findOneByCode($item->getCode());
            if (!$product) {
                $product = new Product();
            }
            $product->setName($item->getName());
            $product->setCode($item->getCode());
            $product->setDescription($item->getDescription());
            $product->setPrice($item->getPrice());
            $product->setQuantity($item->getQuantity());
            if ($item->getDiscontinued()) {
                $product->setDiscontinuedAt(new \DateTime());
            }

            $em->persist($product);
            // Do not complete the transaction if run in test mode.
            if ($input->getArgument('test') != 'test') {
                $em->flush();
            }

            $this->effected[] = "Product code: {$item->getCode()} imported.";
        }

        // Alert final results in table.
        $table = new Table($output);
        $table
            ->setHeaders(array('Status', 'Count'))
            ->setRows([
                ['total', count($items)],
                ['skipped', count($this->affected)],
                ['successful', count($this->effected)],
            ]);
        $table->render();

        // Return affected products.
        if (!empty($this->affected)) {
            foreach ($this->affected as $value) {
                $output->writeln($value);
            }
        }
    }
}
