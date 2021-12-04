<?php
if (!isset($_GET["action"])) {
    $_GET['action'] = null;
}

session_start();

switch ($_GET['action']) {
    case 'store':
        store();
        break;
    case 'get':
        get();
        exit;
    case 'update':
        update();
        break;
    case 'destroy':
        destroy();
        break;
    default:
        index();
        break;
}


/**
 * Load a CRUD page
 *
 * @return void
 */
function index()
{ ?>
    <!DOCTYPE html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pizza Pizza</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    </head>
    <div class="container">
        <div class="row">
            <div class="col border-bottom">
                <h3 class="text-center">Pizza project</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <h4 id="action"></h4>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Topping:</label>
                    <input id="topping" class="form-control"">
                </div>
                <button type=" button" class="btn btn-primary" onclick="save()">Save</button>
                </div>
                <div class="col-md-8">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="toppings">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <body>

        </body>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="./jquery.min.js"> </script>
        <script src="./index.js"> </script>


        </html>



    <?php
}

/**
 * Store a new topping
 *
 * @return void
 */
function store()
{
    $response = [
        'data' => 'An error occurred to store the topping'
    ];

    if (isset($_GET['topping']) && strlen(trim($_GET["topping"])) > 0) {
        if (!isset($_SESSION['toppings'])) {
            $_SESSION['toppings'] = [];
        }
        $exists = false;

        foreach ($_SESSION['toppings'] as $value) {
            if ($_GET['topping'] == $value) {
                $exists = true;
                $response['data'] = "The topping already exists";
                break;
            }
        }
        if (!$exists) {
            array_push($_SESSION['toppings'], $_GET['topping']);
            $response['data'] = "Success";
        }
    }
    echo json_encode($response);
    exit;
}


/**
 * Get a list of toppings
 *
 * @return void
 */
function get()
{
    $response = [
        'data' => []
    ];
    if (isset($_SESSION['toppings'])) {
        $response['data'] = $_SESSION['toppings'];
    }
    echo json_encode($response);
}


/**
 * Update the specified topping.
 *
 * @return void
 */
function update()
{
    $response = [
        'data' => "An error occurred to update the topping."
    ];

    if (
        isset($_GET['id']) && strlen(trim($_GET['id'])) > 0 &&
        isset($_GET['topping']) && strlen(trim($_GET['topping'])) > 0 &&
        isset($_SESSION["toppings"]) && count($_SESSION["toppings"]) >= $_GET['id'] &&
        is_numeric($_GET["id"])
    ) {
        $exists = false;

        foreach ($_SESSION["toppings"] as $key => $value) {
            if (($key != $_GET['id'] && $_GET['topping'] == $value)) {
                $response['data'] = "The topping already exists.";
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $_SESSION['toppings'][$_GET['id']] = $_GET['topping'];
            $response['data'] = "Success";
        }
    }

    echo json_encode($response);
    exit;
}

/**
 * Delete the specified topping.
 *
 * @return void
 */
function destroy()
{
    $response = [
        'data' => "An error occurred to delete the topping."
    ];

    if (isset($_GET['id']) && strlen(trim($_GET['id'])) > 0 && is_numeric($_GET["id"])) {

        if (count($_SESSION['toppings']) >= $_GET['id']) {
            array_splice($_SESSION['toppings'], $_GET['id'], 1);
            $response['data'] = 'Success';
        }
    }
    echo json_encode($response);
    exit;
}
