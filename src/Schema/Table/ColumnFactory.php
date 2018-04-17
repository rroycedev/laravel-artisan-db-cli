<?php
/**
 * @file
 * @author  Lightly Salted Software Ltd
 * @date    March 2015
 */

namespace Roycedev\DbCli\Schema\Table;

/**
 * Class ColumnFactory
 */
class ColumnFactory implements ColumnFactoryInterface
{
    /**
     * @param string $name        sql column name
     * @param string $description textual description for comment field of column
     * @param string $sqlType     eg varchar(9)
     * @return Column
     */
    public function create($name, $description, $sqlType)
    {
        $allPatterns = require __DIR__ . '/../Patterns.php';

        $columnPatterns = $allPatterns['column'];

        foreach ($columnPatterns as $regex => $className) {
            if (!preg_match('/^' . $regex . '/i', $sqlType, $matches)) {
                continue;
            }

            $collate = "";
            $charset = "";

            if (count($matches) > 2) {
                if (preg_match($allPatterns['table']['collate'], $matches[2], $collateMatches)) {
                    $collate = str_replace(";", "", $collateMatches[1]);
                }
                if (preg_match($allPatterns['table']['charset'], $matches[2], $charsetMatches)) {
			echo "CHARET BEFORE [" . $matches[1] . "]n";
                    $charset = str_replace(";", "", $charsetMatches[1]);
			echo "CHARSET AFTER [" . $charset . "]\n";

			exit(1);
                }
            }

            $allowNull = stripos($sqlType, ' not null') === false;
            $unique = stripos($sqlType, " unique") !== false;

            $instance = new $className($name, $description, $allowNull, $unique, $charset, $collate,
                isset($matches[1]) ? $matches[1] : null,
                isset($matches[2]) ? $matches[2] : null
            );

            return $instance;
        }
        throw new \InvalidArgumentException('Unknown SQL field type "' . $sqlType . '" for field ' . $name);
    }
}
