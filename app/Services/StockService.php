<?php

namespace App\Services;

use App\Models\DetailPeriksa;
use App\Models\Obat;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{
    /**
     * Deduct drug stock when saving examination results
     * 
     * @param array $drugs Array of ['id_obat' => drug_id, 'jumlah' => quantity]
     * @return bool
     * @throws Exception
     */
    public static function deductStock(array $drugs)
    {
        try {
            DB::beginTransaction();
            
            // Check if all drugs have sufficient stock
            foreach ($drugs as $drug) {
                $obat = Obat::findOrFail($drug['id_obat']);
                
                if ($obat->stok < $drug['jumlah']) {
                    throw new Exception("Stok obat '{$obat->nama_obat}' tidak cukup. Stok tersedia: {$obat->stok}, diminta: {$drug['jumlah']}");
                }
            }
            
            // All checks passed, deduct stock
            foreach ($drugs as $drug) {
                $obat = Obat::findOrFail($drug['id_obat']);
                $obat->decrement('stok', $drug['jumlah']);
            }
            
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Check if a drug has low stock
     * 
     * @param int $obatId
     * @param int $threshold Default 10
     * @return bool
     */
    public static function isLowStock($obatId, $threshold = 10)
    {
        $obat = Obat::find($obatId);
        return $obat && $obat->stok <= $threshold;
    }
    
    /**
     * Get all drugs with low stock
     * 
     * @param int $threshold Default 10
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLowStockDrugs($threshold = 10)
    {
        return Obat::where('stok', '<=', $threshold)->get();
    }
}
