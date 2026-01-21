const btn=document.getElementById("getAllProduct");
if (btn) {
    btn.addEventListener("click", function(){
        window.location.href="GetAllProducts.php";
    });
}

function approveProduct(productId) {
    if (confirm('Are you sure you want to approve this product?')) {
        fetch('../../controllers/approve_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId + '&action=approve'
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}

function rejectProduct(productId) {
    if (confirm('Are you sure you want to reject this product?')) {
        fetch('../../controllers/approve_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId + '&action=reject'
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}

function deleteProduct(productId) {
    if (confirm('Are you sure you want to permanently delete this product?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'products.php';

        const productIdInput = document.createElement('input');
        productIdInput.type = 'hidden';
        productIdInput.name = 'product_id';
        productIdInput.value = productId;

        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_product';
        deleteInput.value = '1';

        form.appendChild(productIdInput);
        form.appendChild(deleteInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function showStatusMenu(productId, currentStatus) {
   
    document.querySelectorAll('.status-menu').forEach(menu => {
        menu.style.display = 'none';
    });

    
    const menu = document.getElementById('status-menu-' + productId);
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}

function changeStatus(productId, newStatus) {
    const action = newStatus === 'approved' ? 'approve' : 'reject';
    const statusText = action;
    if (confirm(`Are you sure you want to ${statusText} this product?`)) {
        fetch('../../controllers/approve_product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'product_id=' + productId + '&action=' + action
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}


document.addEventListener('click', function(event) {
    if (!event.target.classList.contains('status-badge')) {
        document.querySelectorAll('.status-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});