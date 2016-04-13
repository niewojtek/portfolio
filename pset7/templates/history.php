<table class="table table-striped">

    <thead>
        <tr>
            <th>Transaction</th>
            <th>Date/Time</th>
            <th>Symbol</th>
            <th>Shares</th>
            <th>Price</th>
        </tr>
    </thead>

    <tbody>
           <?php foreach ($history_positions as $history_position): ?>

    <tr>
        <td><?= $history_position["transaction"] ?></td>
        <td><?= date('d/m/y, g:i A',strtotime($history_position["date"])) ?></td>        
        <td><?= $history_position["symbol"] ?></td>
        <td><?= $history_position["shares"] ?></td>
        <td>$<?= number_format($history_position["price"], 2) ?></td>
    </tr>

<?php endforeach ?>

    </tbody>

</table>
