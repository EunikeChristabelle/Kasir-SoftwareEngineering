<?php include 'db_connect.php';
// Query untuk mengambil jumlah kategori
$category_count = $conn->query("SELECT COUNT(*) as count FROM categories")->fetch_assoc()['count'];

// Query untuk mengambil jumlah order
$order_count = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];

// Query untuk mengambil jumlah produk
$product_count = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];


?>

<style>
span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}

.imgs {
    margin: .5em;
    max-width: calc(100%);
    max-height: calc(100%);
}

.imgs img {
    max-width: calc(100%);
    max-height: calc(100%);
    cursor: pointer;
}

#imagesCarousel,
#imagesCarousel .carousel-inner,
#imagesCarousel .carousel-item {
    height: 60vh !important;
    background: black;
}

#imagesCarousel .carousel-item.active {
    display: flex !important;
}

#imagesCarousel .carousel-item-next {
    display: flex !important;
}

#imagesCarousel .carousel-item img {
    margin: auto;
}

#imagesCarousel img {
    width: auto !important;
    height: auto !important;
    max-height: calc(100%) !important;
    max-width: calc(100%) !important;
}

//utk kotak

.box {
    border: 1px solid #ccc;
    padding: 20px;
    margin: 10px;
    text-align: center;
    font-size: 1.5rem;
    position: relative;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    transition: transform 0.3s, box-shadow 0.3s;
}

.box .summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
    color: #007bff;
}

.box h4 {
    margin-bottom: 10px;
    font-size: 1.25rem;
    color: #333;
}

.box p {
    font-size: 2rem;
    font-weight: bold;
    color: #007bff;
}

.box:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
</style>

<div class="container-fluid">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Hello ". $_SESSION['login_name']."!" ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box">
                                <span class="float-right summary_icon">&#128200;</span>
                                <h4>Categories</h4>
                                <p><?php echo $category_count; ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box">
                                <span class="float-right summary_icon">&#128202;</span>
                                <h4>Orders</h4>
                                <p><?php echo $order_count; ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="box">
                                <span class="float-right summary_icon">&#128218;</span>
                                <h4>Products</h4>
                                <p><?php echo $product_count; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#manage-records').submit(function(e) {
    e.preventDefault()
    start_load()
    $.ajax({
        url: 'ajax.php?action=save_track',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp) {
            resp = JSON.parse(resp)
            if (resp.status == 1) {
                alert_toast("Data successfully saved", 'success')
                setTimeout(function() {
                    location.reload()
                }, 800)

            }

        }
    })
})
$('#tracking_id').on('keypress', function(e) {
    if (e.which == 13) {
        get_person()
    }
})
$('#check').on('click', function(e) {
    get_person()
})

function get_person() {
    start_load()
    $.ajax({
        url: 'ajax.php?action=get_pdetails',
        method: "POST",
        data: {
            tracking_id: $('#tracking_id').val()
        },
        success: function(resp) {
            if (resp) {
                resp = JSON.parse(resp)
                if (resp.status == 1) {
                    $('#name').html(resp.name)
                    $('#address').html(resp.address)
                    $('[name="person_id"]').val(resp.id)
                    $('#details').show()
                    end_load()

                } else if (resp.status == 2) {
                    alert_toast("Unknow tracking id.", 'danger');
                    end_load();
                }
            }
        }
    })
}
</script>