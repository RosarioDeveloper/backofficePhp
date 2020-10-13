<?php
//require_once "../../../../modules/store/orders/mail/mail-style.php";
$req = $_POST['req'];
$cart = $_POST['cart'];

?>



<div class="mail-content" style="width: 595px; height: 842;">
   <div class="mail-title mb20">
      <h2>Obrigado pela sua encomenda</h2>
   </div>

   <div class="mail-body">
      <p class="mail-text mb20">
         Olá, <?=$req['delivery']['nome']?>.<br>
         Obrigado pelo seu pedido. No momento estamos aguardando a confirmação do<br>
         pagamento. Enquanto isso, veja os detalhes da sua encomenda para sua referência:
      </p>

      <h3 class="title mb20">Informações Bancárias</h3>
      <h4 class="subtitle">Bazara Store</h4>
      <p class="mb20">
         Banco: BAI<br>
         Número da conta: 137894821.10.001<br>
         IBAN: AO06.0040.0000.3789.4821.1010.
      </p>

      <h4 class="subtitle mb20">[Encomenda: #<?=str_pad($req['id'],4,'0', STR_PAD_LEFT)?></h4>
      <div class="mail-table mb20">
         <table cellspacing="0" cellpadding="0" style="width: 100%;">
            <thead>
               <th class="left">Produto</th>
               <th class="center">Qtd</th>
               <th class="right">Total</th>
            </thead>

            <tbody>
                <?php foreach($cart['items'] as $item){?>
               <tr>
                  <td class="left"></td>
                  <td class="center">qtd</td>
                  <td class="right">0,00</td>
               </tr>
               <?php }?>
            </tbody>

            <tfoot>
               <tr>
                  <td colspan="2" class="right">Subtotal</td>
                  <td class="right">0,00</td>
               </tr>
               <tr>
                  <td colspan="2" class="right">Entrega</td>
                  <td class="right">0,00</td>
               </tr>
               <tr>
                  <td colspan="2" class="right">Forma de pagamento</td>
                  <td class="right">0,00</td>
               </tr>
               <tr>
                  <td colspan="2" class="right bold">Total geral</td>
                  <td class="right bold">0,00</td>
               </tr>
            </tfoot>
         </table>
      </div>

      <h4 class="subtitle mb20">Endereço da entrega</h4>
      <p>Banco: BAI<br>
      Número da conta: 137894821.10.001<br>
      IBAN: AO06.0040.0000.3789.4821.1010.2</p>
   </div>

   <div class="mail-footer">

   </div>
</div>
