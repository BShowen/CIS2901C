<div class="card">
  <div class="card_title">
    <h1>Invoices for sale <?php echo $sale_id ?></h1>
  </div>
  <hr>
  <div class="card_details">
    <div class="table_container">
      <table>
        <thead>
          <tr class="no-hover">
            <th scope="col">Sale number</th>
            <th scope="col">Invoice number</th>
            <th scope="col">Sent date</th>
            <th scope="col">Due date</th>
            <th scope="col">Total</th>
            <th scope="col">Web link</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php echo $invoice_table_rows; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>