<?php
include '../database.php';
$admin_id = 0;
if (isset($_SESSION['admin_id'])) {
    $admin_id = intval($_SESSION['admin_id']);
}

$select_fields = "SELECT b.id AS b_id,b.transaction_number AS b_transaction_number, b.is_new AS b_is_new, b.amount AS b_amount, DATE(b.transaction_date) AS transaction_date, TIME(b.transaction_date) AS transaction_time, p.id AS p_id, p.package_name AS p_name, c.id AS c_id, c.company_name AS c_company_name FROM bill b LEFT JOIN packages p ON b.package_id = p.id LEFT JOIN company c ON b.company_id = c.id WHERE p.is_delete = '0' ORDER BY transaction_date ASC";
$result = mysqli_query($conn, $select_fields);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

?>

<table id="datatable" class="table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Number</th>
            <th>Company Name</th>
            <th>Package Name</th>
            <th>Amount</th>
            <th>Transaction Number</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $number = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $number++ . "</td>";
            echo "<td>" . $row['c_company_name'] . "</td>";
            echo "<td>" . $row['p_name'] . "</td>";
            echo "<td>" . $row['b_amount'] . "</td>";
            echo "<td>" . $row['b_transaction_number'] . "</td>";
            echo "<td>" . $row['transaction_date'] . "</td>";
            echo "<td>" . $row['transaction_time'] . "</td>";
            echo "<td><button class='btn btn-sm btn-success approve-btn ". (!$row['b_is_new'] ? "new":"")."'" ."data-bid='" . $row['b_id'] . "' data-pid='" . $row['p_id'] . "' data-cid='" . $row['c_id'] . "' style='background-color: #11ca00;'>Approve</button></td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>

<!-- Modal for displaying confirmation -->
<div id="myModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Are you going to approve this company's purchase?</p>
        <form id="updateForm" method="POST" action="?content=updatepackage">
            <input type="hidden" id="bidDisplay" name="bid">
            <input type="hidden" id="pidDisplay" name="pid">
            <input type="hidden" id="cidDisplay" name="cid">
            <div class="btn-container">
                <button type="button" class="btn btn-cancel" style="background-color: #f44336;">Cancel</button>
                <input type="submit" class="btn btn-success" value="Approve" style="background-color: #11ca00;">
            </div>
        </form>
    </div>
</div>

<!-- Toast for success message -->
<?php if (isset($_SESSION['success'])): ?>
<div class="toast-container">
    <div id="sessionToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1500">
        <div class="toast-header">
            <strong class="me-auto"><?php echo $_SESSION['success']; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toastEl = document.getElementById('sessionToast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }

        // Modal logic
        const modal = document.getElementById('myModal');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = modal.querySelector('.btn-cancel');

        const approveBtns = document.querySelectorAll('.approve-btn');
        approveBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const bid = this.getAttribute('data-bid');
                const pid = this.getAttribute('data-pid');
                const cid = this.getAttribute('data-cid');

                document.getElementById('bidDisplay').value = bid;
                document.getElementById('pidDisplay').value = pid;
                document.getElementById('cidDisplay').value = cid;
                modal.style.display = "block";
            });
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = "none";
        });
        cancelBtn.addEventListener('click', function() {
            modal.style.display = "none";
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 1000);
            }, 2000);
        }
    });
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        text-decoration: none;
        cursor: pointer;
    }

    .btn-container {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 5px;
        cursor: pointer;
    }
    .btn:hover {
        background-color: darkgreen;
    }

    .btn-cancel {
        background-color: #f44336;
        color: white;
        border: none;
    }

    .btn-success {
        background-color: #11ca00;
        color: white;
        border: none;
    }

    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #11ca00;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        z-index: 9999;
        transition: opacity 1s ease-out;
    }
    .new {
        pointer-events: none; 
        opacity: 0.6;
        cursor: not-allowed; 
    }
    .new:hover {
        background-color: #ccc;
    }
</style>
