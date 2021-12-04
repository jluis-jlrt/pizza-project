window.onload = index;

var action = "store";
var id = 0;

function index() {
  $("#action").text("New topping");
  $.ajax({
    url: "index.php?action=get",
    success: function (response) {
      var json = {};
      try {
        json = jQuery.parseJSON(response);
        $("#toppings").empty();
        $.each(json.data, function (key, value) {
          $("#toppings").append(`
            <tr>
                <td>${key + 1}</td>
                <td>${value}</td>
                <td>
                <button class='btn btn-danger' onclick="destroy(${key})">Delete</button>
                <button class='btn btn-warning' onclick="edit(${key}, '${value}')">Edit</button>
                </td>
            </tr>
            `);
        });
      } catch (error) {
        warning("Internal server error");
      }
    },
    error: function () {
      warning("An error occurred.");
    },
  });
}

function save() {
  if (action == "store") {
    store();
  } else {
    update();
  }
}

function store() {
  $.ajax({
    url: "index.php?action=store",
    data: {
      topping: $("#topping").val(),
    },
    success: function (response) {
      var json = {};
      try {
        json = jQuery.parseJSON(response);
        if (json.data == "Success") {
          index();
          success();
          $("#topping").val("");
        } else {
          warning(json.data);
        }
      } catch (error) {
        warning("Internal server error.");
      }
    },
    error: function () {
      warning("An error occurred.");
    },
  });
}

function edit(key, name) {
  id = key;
  $("#topping").val(name);
  action = "update";
  $("#action").text("Update topping");
}

function update() {
  $.ajax({
    url: "index.php?action=update",
    data: {
      topping: $("#topping").val(),
      id: id,
    },
    success: function (response) {
      var json = {};
      try {
        json = jQuery.parseJSON(response);
        if (json.data == "Success") {
          index();
          success();
          $("#topping").val("");
          action = "store";
          $("#action").text("New topping");
        } else {
          warning(json.data);
        }
      } catch (error) {
        warning("Internal server error.");
      }
    },
    error: function () {
      warning("An error occurred.");
    },
  });
}

function destroy(id) {
  var json = {};
  $.ajax({
    url: `index.php?action=destroy&id=${id}`,
    success: function (response) {
      try {
        json = jQuery.parseJSON(response);
        if (json.data == "Success") {
          index();
          success();
        } else {
          warning(json.data);
        }
      } catch (error) {
        warning("Internal server error.");
      }
    },
    error: function () {
      warning("An error occurred.");
    },
  });
}

function success() {
  Swal.fire({
    icon: "success",
    confirmButtonText: "Ok",
    timer: 1000,
  });
}

function warning(message) {
  Swal.fire({
    title: message,
    icon: "warning",
    confirmButtonText: "Ok",
  });
}
