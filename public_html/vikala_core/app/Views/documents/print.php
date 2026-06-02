<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= esc($document['document_number']) ?></title>
    <style>
        /* Extreme Minimalism (Whitespace Layout & Ink-Saving Mode) */
        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 16pt;
            color: #000000;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        
        .header-title {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .border-bottom { border-bottom: 1px solid #000; }
        .border-top { border-top: 1px solid #000; }
        .border-all { border: 1px solid #000; }
        
        .item-table th, .item-table td {
            border: 1px solid #000;
        }
        
        .no-border { border: none !important; }
        .spacer { height: 20px; }
    </style>
</head>
<body>

    <!-- Header Section -->
    <table>
        <tr>
            <td width="60%">
                <div class="header-title"><?= esc($company['name']) ?></div>
                <div><?= esc($company['address']) ?></div>
                <div>เลขประจำตัวผู้เสียภาษี: <?= esc($company['tax_id']) ?> สาขา: <?= esc($company['branch_code'] ?? '00000') ?></div>
            </td>
            <td width="40%" class="text-right">
                <div class="header-title">
                    <?php
                        if ($document['document_type'] === 'Invoice') echo 'ใบแจ้งหนี้';
                        elseif ($document['document_type'] === 'Receipt') echo 'ใบเสร็จรับเงิน';
                        elseif ($document['document_type'] === 'TaxInvoice') echo 'ใบกำกับภาษี / ใบเสร็จรับเงิน';
                        else echo 'เอกสาร';
                    ?>
                </div>
                <div>เลขที่เอกสาร: <span class="font-bold"><?= esc($document['document_number']) ?></span></div>
                <div>วันที่: <?= esc(date('d/m/Y', strtotime($document['created_date']))) ?></div>
                <?php if(!empty($document['reference_number'])): ?>
                    <div>อ้างอิง: <?= esc($document['reference_number']) ?></div>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="spacer"></div>

    <!-- Customer Section (Snapshot) -->
    <table>
        <tr>
            <td class="border-all">
                <div class="font-bold">ลูกค้า / Customer:</div>
                <div><?= esc($document['customer_name']) ?></div>
                <div><?= esc($document['customer_address']) ?></div>
                <div>เลขประจำตัวผู้เสียภาษี: <?= esc($document['customer_tax_id']) ?> สาขา: <?= esc($document['customer_branch']) ?></div>
            </td>
        </tr>
    </table>

    <div class="spacer"></div>

    <!-- Items Section -->
    <table class="item-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">ลำดับ</th>
                <th width="45%" class="text-left">รายการ / Description</th>
                <th width="15%" class="text-center">จำนวน</th>
                <th width="15%" class="text-right">ราคาต่อหน่วย</th>
                <th width="20%" class="text-right">จำนวนเงิน</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            if(!empty($items)): 
                foreach($items as $item): 
            ?>
            <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td><?= esc($item['product_name']) ?></td>
                <td class="text-center"><?= number_format($item['quantity'], 2) ?></td>
                <td class="text-right"><?= number_format($item['unit_price'], 2) ?></td>
                <td class="text-right"><?= number_format($item['amount'], 2) ?></td>
            </tr>
            <?php 
                endforeach; 
            else:
            ?>
            <tr>
                <td colspan="5" class="text-center">ไม่มีรายการสินค้า</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="spacer"></div>

    <!-- Summary Section -->
    <table>
        <tr>
            <td width="60%" valign="top">
                <?php if(isset($company['show_remarks']) && $company['show_remarks'] == 1): ?>
                    <div class="font-bold border-bottom" style="width:100px;">หมายเหตุ</div>
                    <div style="font-size:14pt; margin-top:5px;">
                        - การชำระเงินด้วยเช็ค จะสมบูรณ์เมื่อบริษัทได้รับเงินตามเช็คเรียบร้อยแล้ว<br>
                        - หากมีข้อผิดพลาด โปรดแจ้งกลับภายใน 7 วัน
                    </div>
                <?php endif; ?>
            </td>
            <td width="40%">
                <table class="item-table">
                    <tr>
                        <td class="text-right font-bold" width="60%">รวมเป็นเงิน:</td>
                        <td class="text-right" width="40%"><?= number_format($document['total_amount'], 2) ?></td>
                    </tr>
                    <?php if($document['discount_amount'] > 0): ?>
                    <tr>
                        <td class="text-right font-bold">ส่วนลด:</td>
                        <td class="text-right"><?= number_format($document['discount_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($document['vat_amount'] > 0): ?>
                    <tr>
                        <td class="text-right font-bold">ภาษีมูลค่าเพิ่ม (<?= number_format($document['vat_rate'], 0) ?>%):</td>
                        <td class="text-right"><?= number_format($document['vat_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-right font-bold">ยอดเงินสุทธิ:</td>
                        <td class="text-right font-bold"><?= number_format($document['net_amount'], 2) ?></td>
                    </tr>
                    <?php if($document['wht_amount'] > 0): ?>
                    <tr>
                        <td class="text-right font-bold">หัก ณ ที่จ่าย (<?= number_format($document['wht_percentage'], 0) ?>%):</td>
                        <td class="text-right"><?= number_format($document['wht_amount'], 2) ?></td>
                    </tr>
                    <tr>
                        <td class="text-right font-bold">ยอดชำระสุทธิ:</td>
                        <td class="text-right font-bold"><?= number_format($document['net_amount'] - $document['wht_amount'], 2) ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

    <div class="spacer"></div>
    <div class="spacer"></div>

    <!-- Signatures -->
    <?php if(isset($company['show_signatures']) && $company['show_signatures'] == 1): ?>
    <table>
        <tr>
            <td width="50%" class="text-center">
                <br><br>
                ___________________________________<br>
                ผู้รับสินค้า / ผู้รับบริการ<br>
                วันที่: _____/_____/_______
            </td>
            <td width="50%" class="text-center">
                <br><br>
                ___________________________________<br>
                ผู้มีอำนาจลงนาม / ผู้รับเงิน<br>
                วันที่: _____/_____/_______
            </td>
        </tr>
    </table>
    <?php endif; ?>

</body>
</html>
