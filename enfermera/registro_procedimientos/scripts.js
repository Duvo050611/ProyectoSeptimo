document.addEventListener("DOMContentLoaded", function () {
  // Prevent right-click context menu
  document.oncontextmenu = function () {
    return false;
  };

  // Initialize voice recognition for nota de enfermería
  function initVoiceRecognition() {
    const botonesDivs = document.querySelectorAll(".botones");
    botonesDivs.forEach(function (botonesDiv) {
      const grabarBtn = botonesDiv.querySelector(".grabar-nota");
      const detenerBtn = botonesDiv.querySelector(".detener-nota");
      const reproducirBtn = botonesDiv.querySelector(".reproducir-nota");
      const campoNota =
        botonesDiv.parentElement.querySelector(".nota-enfermeria");

      if (!grabarBtn || !detenerBtn || !reproducirBtn || !campoNota) return;

      // Check for speech recognition support
      if (!window.webkitSpeechRecognition) {
        alert(
          "Reconocimiento de voz no soportado en este navegador. Use Chrome para esta función."
        );
        grabarBtn.disabled = true;
        detenerBtn.disabled = true;
        return;
      }

      const reconocimiento = new webkitSpeechRecognition();
      reconocimiento.lang = "es-ES";
      reconocimiento.continuous = true;
      reconocimiento.interimResults = false;

      reconocimiento.onresult = (event) => {
        const results = event.results;
        const frase = results[results.length - 1][0].transcript;
        campoNota.value += frase + " ";
      };

      grabarBtn.addEventListener("click", () => {
        reconocimiento.start();
      });

      detenerBtn.addEventListener("click", () => {
        reconocimiento.abort();
      });

      reproducirBtn.addEventListener("click", () => {
        const speech = new SpeechSynthesisUtterance(campoNota.value);
        window.speechSynthesis.speak(speech);
      });
    });
  }

  // Validate signos vitales inputs
  function validateSignosVitales(fila) {
    const inputs = fila.querySelectorAll("input[required]");
    let valid = true;

    inputs.forEach((input) => {
      const value = input.value.trim();
      if (!value) {
        input.style.borderColor = "red";
        valid = false;
        return;
      }

      switch (input.name) {
        case "sistg":
          const sistg = parseFloat(value);
          if (isNaN(sistg) || sistg < 50 || sistg > 200) {
            alert("Presión sistólica debe estar entre 50 y 200 mmHg");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
        case "diastg":
          const diastg = parseFloat(value);
          if (isNaN(diastg) || diastg < 30 || diastg > 120) {
            alert("Presión diastólica debe estar entre 30 y 120 mmHg");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
        case "fcardg":
          const fcardg = parseFloat(value);
          if (isNaN(fcardg) || fcardg < 30 || fcardg > 200) {
            alert("Frecuencia cardíaca debe estar entre 30 y 200 bpm");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
        case "frespg":
          const frespg = parseFloat(value);
          if (isNaN(frespg) || frespg < 8 || frespg > 40) {
            alert("Frecuencia respiratoria debe estar entre 8 y 40 rpm");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
        case "satg":
          const satg = parseFloat(value.replace("%", ""));
          if (isNaN(satg) || satg < 50 || satg > 100) {
            alert("Saturación de oxígeno debe estar entre 50% y 100%");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
        case "tempg":
          const tempg = parseFloat(value);
          if (isNaN(tempg) || tempg < 34 || tempg > 42) {
            alert("Temperatura debe estar entre 34°C y 42°C");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
        case "hora_signos":
          if (!/^\d{2}:\d{2}$/.test(value)) {
            alert("Formato de hora inválido");
            input.style.borderColor = "red";
            valid = false;
          }
          break;
      }
    });

    return valid;
  }

  // Add new signos vitales row
  function agregarFilaSignosVitales(btnAgregar, idTratamiento) {
    const tabla = btnAgregar.closest("table");
    if (!tabla) {
      console.error("No se encontró la tabla");
      return;
    }
    const tbody = tabla.getElementsByTagName("tbody")[0];
    const btnRow = btnAgregar.closest(".btn-agregar-row");

    const nuevaFila = document.createElement("tr");
    nuevaFila.className = "durante-cirugia-row";
    nuevaFila.innerHTML = `
            <td><strong>Signos Vitales</strong><br><small class="text-muted">Registro adicional #${
              tbody.querySelectorAll(".durante-cirugia-row").length + 1
            }</small></td>
            <td><input type="text" class="form-control" name="sistg" placeholder="ej: 120" required></td>
            <td><input type="text" class="form-control" name="diastg" placeholder="ej: 80" required></td>
            <td><input type="text" class="form-control" name="fcardg" placeholder="ej: 75" required></td>
            <td><input type="text" class="form-control" name="frespg" placeholder="ej: 20" required></td>
            <td><input type="text" class="form-control" name="satg" placeholder="ej: 98%" required></td>
            <td><input type="text" class="form-control" name="tempg" placeholder="ej: 36.5" required></td>
            <td><input type="time" class="form-control" name="hora_signos" required></td>
            <td>
                <button type="button" class="btn btn-primary btn-sm enviar-signos" data-tratamiento="${idTratamiento}">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <button type="button" class="btn btn-warning btn-sm editar-signos" style="display: none; margin-left: 5px;" data-tratamiento="${idTratamiento}">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </td>
        `;

    tbody.insertBefore(nuevaFila, btnRow);

    const btnGuardar = nuevaFila.querySelector(".enviar-signos");
    const btnEditar = nuevaFila.querySelector(".editar-signos");

    btnGuardar.addEventListener("click", () =>
      enviarSignosVitales(nuevaFila, idTratamiento)
    );
    btnEditar.addEventListener("click", () =>
      editarSignosVitales(nuevaFila, idTratamiento)
    );
  }

  // Submit signos vitales via AJAX
  function enviarSignosVitales(fila, idTratamiento) {
    if (!validateSignosVitales(fila)) {
      return;
    }

    const btnGuardar = fila.querySelector(".enviar-signos");
    const textoOriginal = btnGuardar.innerHTML;
    btnGuardar.disabled = true;
    btnGuardar.innerHTML =
      '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    const datos = new FormData();
    fila.querySelectorAll("input[required]").forEach((input) => {
      datos.append(input.name, input.value);
    });
    datos.append("id_tratamiento", idTratamiento);
    datos.append(
      "csrf_token",
      document.querySelector('input[name="csrf_token"]').value
    );

    fetch("insertar_signos_vitales.php", {
      method: "POST",
      body: datos,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          fila.classList.add("guardando");
          alert("Signos vitales guardados correctamente");

          btnGuardar.innerHTML = '<i class="fas fa-check"></i> Guardado';
          btnGuardar.className = "btn btn-success btn-sm btn-guardado";
          btnGuardar.disabled = true;

          const btnEditar = fila.querySelector(".editar-signos");
          if (btnEditar) {
            btnEditar.style.display = "inline-block";
          }

          fila.querySelectorAll("input[required]").forEach((input) => {
            input.readOnly = true;
            input.style.backgroundColor = "#c3e6cb";
            input.style.color = "#155724";
            input.style.fontWeight = "500";
          });

          setTimeout(() => {
            fila.classList.remove("guardando");
            fila.classList.add("guardado");
          }, 1000);
        } else {
          throw new Error(data.message || "Error desconocido");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error al guardar los signos vitales: " + error.message);
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = textoOriginal;
      });
  }

  // Enable editing of signos vitales
  function editarSignosVitales(fila, idTratamiento) {
    const inputs = fila.querySelectorAll("input[required]");
    const btnEditar = fila.querySelector(".editar-signos");
    const btnGuardar = fila.querySelector(".enviar-signos");

    inputs.forEach((input) => {
      input.readOnly = false;
      input.style.backgroundColor = "";
      input.style.color = "";
      input.style.fontWeight = "";
    });

    btnEditar.style.display = "none";
    btnGuardar.disabled = false;
    btnGuardar.innerHTML = '<i class="fas fa-save"></i> Actualizar';
    btnGuardar.className = "btn btn-primary btn-sm enviar-signos";

    fila.classList.remove("guardado");
  }

  // Collect form data
  function recopilarDatosFormulario(formulario) {
    const datos = {};
    const inputs = formulario.querySelectorAll("input, select, textarea");
    const signosVitales = [];

    inputs.forEach((input) => {
      if (
        input.name &&
        input.name !== "tratamientos_seleccionados" &&
        input.name !== "csrf_token"
      ) {
        if (
          [
            "sistg",
            "diastg",
            "fcardg",
            "frespg",
            "satg",
            "tempg",
            "hora_signos",
          ].includes(input.name)
        ) {
          const fila = input.closest("tr");
          if (fila && !fila.classList.contains("btn-agregar-row")) {
            let signosActuales = signosVitales.find(
              (signos) => signos.fila === fila
            );
            if (!signosActuales) {
              signosActuales = { fila: fila };
              signosVitales.push(signosActuales);
            }
            signosActuales[input.name] = input.value;
          }
        } else if (input.name.endsWith("[]")) {
          const baseName = input.name.slice(0, -2);
          if (!datos[baseName]) {
            datos[baseName] = [];
          }
          datos[baseName].push(input.value);
        } else {
          datos[input.name] = input.value;
        }
      }
    });

    if (signosVitales.length > 0) {
      datos.signos_vitales_multiples = signosVitales.map((signos) => {
        const { fila, ...signosLimpios } = signos;
        return signosLimpios;
      });
    }

    return datos;
  }

  // Handle LASIK checkbox
  function handleLasikCheckbox() {
    const lasikChecked =
      document.querySelectorAll(".tratamiento-checkbox.lasik-checkbox:checked")
        .length > 0;
    const checkboxesSeleccionados = document.querySelectorAll(
      ".tratamiento-checkbox:checked"
    );

    // Mostrar/ocultar campos LASIK
    const camposLasik = document.getElementById("campos_lasik");
    if (camposLasik) {
      camposLasik.style.display = lasikChecked ? "flex" : "none";
    }

    // Mostrar/ocultar botón cargar tratamientos
    const btnCargar = document.getElementById("btn_cargar_tratamientos");
    if (btnCargar) {
      btnCargar.style.display =
        checkboxesSeleccionados.length > 0 ? "block" : "none";
    }

    // Solo actualizar el título, NO mostrar el formulario automáticamente
    const tituloTratamientos = document.getElementById(
      "titulo_tratamientos_dinamico"
    );

    if (checkboxesSeleccionados.length > 0) {
      let nombresTratamientos = [];
      checkboxesSeleccionados.forEach((checkbox) => {
        const tipo = checkbox.getAttribute("data-tipo");
        nombresTratamientos.push(tipo.toUpperCase());
      });

      // Actualizar título dinámicamente pero mantener formulario oculto
      if (tituloTratamientos) {
        const tituloCompleto =
          'FORMULARIO DE TRATAMIENTOS SELECCIONADOS<br><span style="color: #4a4ed1; font-weight: bold; font-size: 16px;">' +
          nombresTratamientos.join(" - ") +
          "</span>";
        tituloTratamientos.innerHTML = tituloCompleto;
      }
    } else {
      // Ocultar todo cuando no hay tratamientos seleccionados
      const contenedor = document.getElementById("formulario_contenedor");
      const formularioGeneral = document.getElementById("formulario_general");

      if (contenedor) contenedor.style.display = "none";
      if (formularioGeneral) formularioGeneral.style.display = "none";
      if (tituloTratamientos) {
        tituloTratamientos.innerHTML =
          "FORMULARIO DE TRATAMIENTOS SELECCIONADOS";
      }
    }
  }

  // Handle treatment selection and form display
  const checkboxes = document.querySelectorAll(".tratamiento-checkbox");
  const btnCargar = document.getElementById("btn_cargar_tratamientos");
  const contenedor = document.getElementById("formulario_contenedor");
  const formularioGeneral = document.getElementById("formulario_general");
  const tratamientosLista = document.getElementById(
    "tratamientos_seleccionados_lista"
  );
  const tratamientosInput = document.getElementById(
    "tratamientos_seleccionados_input"
  );

  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", handleLasikCheckbox);
  });

  btnCargar.addEventListener("click", function () {
    const checkboxesSeleccionados = document.querySelectorAll(
      ".tratamiento-checkbox:checked"
    );
    const contenedor = document.getElementById("formulario_contenedor");
    const formularioGeneral = document.getElementById("formulario_general");
    const tratamientosLista = document.getElementById(
      "tratamientos_seleccionados_lista"
    );
    const tratamientosInput = document.getElementById(
      "tratamientos_seleccionados_input"
    );

    if (checkboxesSeleccionados.length > 0) {
      // Mostrar el contenedor del formulario
      contenedor.style.display = "block";

      const tratamientosGenerales = [];
      let tieneClasik = false;
      let idLasik = "";

      checkboxesSeleccionados.forEach((checkbox) => {
        const tipo = checkbox.getAttribute("data-tipo");
        if (tipo.toUpperCase().includes("LASIK")) {
          tieneClasik = true;
          idLasik = checkbox.value;
        } else {
          tratamientosGenerales.push({
            id: checkbox.value,
            tipo: tipo,
          });
        }
      });

      // Mostrar formulario general si hay tratamientos no-LASIK
      if (tratamientosGenerales.length > 0) {
        formularioGeneral.style.display = "block";
        if (tratamientosLista) {
          const listaTipos = tratamientosGenerales
            .map(
              (t) =>
                `<span class="badge bg-primary me-1">${t.tipo.toUpperCase()}</span>`
            )
            .join(" ");
          tratamientosLista.innerHTML = listaTipos;
        }
        if (tratamientosInput) {
          tratamientosInput.value = JSON.stringify(
            tratamientosGenerales.map((t) => t.id)
          );
        }
      } else {
        formularioGeneral.style.display = "none";
      }

      // Mostrar formulario LASIK si está seleccionado
      if (tieneClasik) {
        const formularioLasik = document.getElementById(
          "formulario_" + idLasik
        );
        if (formularioLasik) {
          formularioLasik.style.display = "block";
        }
      }

      // Hacer scroll suave al formulario
      contenedor.scrollIntoView({ behavior: "smooth" });
    }
  });

  // Handle medico responsable toggle
  const btnToggleMedico = document.getElementById(
    "btn_toggle_medico_responsable"
  );
  const selectWrap = document.getElementById("select_medico_responsable_wrap");
  if (btnToggleMedico && selectWrap) {
    btnToggleMedico.addEventListener("click", function () {
      selectWrap.style.display =
        selectWrap.style.display === "none" || selectWrap.style.display === ""
          ? "block"
          : "none";
    });
  }

  // Initialize voice recognition
  initVoiceRecognition();

  // Prevent multiple form submissions
  let enviandoFormulario = false;
  document
    .getElementById("formulario_unificado")
    .addEventListener("submit", function (event) {
      event.preventDefault();
      if (enviandoFormulario) {
        alert("El formulario ya se está enviando");
        return;
      }
      enviandoFormulario = true;

      const tratamientosSeleccionados = JSON.parse(
        tratamientosInput.value || "[]"
      );
      if (tratamientosSeleccionados.length === 0) {
        alert("No hay tratamientos seleccionados");
        enviandoFormulario = false;
        return;
      }

      const datosFormulario = recopilarDatosFormulario(this);
      const datosFormularios = {};
      tratamientosSeleccionados.forEach((idTratamiento) => {
        datosFormularios[idTratamiento] = { ...datosFormulario };
      });

      const formData = new FormData();
      formData.append(
        "tratamientos_seleccionados",
        JSON.stringify(tratamientosSeleccionados)
      );
      formData.append("datos_formularios", JSON.stringify(datosFormularios));
      formData.append(
        "csrf_token",
        document.querySelector('input[name="csrf_token"]').value
      );

      fetch("insertar_tratamientos_multiples.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("Tratamientos enviados correctamente");
            window.location.reload();
          } else {
            throw new Error(data.message || "Error desconocido");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error al enviar los tratamientos: " + error.message);
        })
        .finally(() => {
          enviandoFormulario = false;
        });
    });
 /*  $(document).ready(function () {
    // Initialize DataTables for Medications
    var tableME = $("#exampleME").DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json",
      },
      columnDefs: [
        { orderable: false, targets: -1 }, // Disable sorting on Actions column
      ],
    });

    var tableMEDC = $("#exampleMEDC").DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json",
      },
      columnDefs: [
        { orderable: false, targets: -1 }, // Disable sorting on Actions column
      ],
    });

    // Search functionality for Medications in Transit
    $("#search_nuevoME").on("keyup", function () {
      tableME.search(this.value).draw();
    });

    // Search functionality for Confirmed Medications
    $("#search_nuevoMEDC").on("keyup", function () {
      tableMEDC.search(this.value).draw();
    });

    // Fetch Medications on page load
    function fetchMedicamentos() {
      $.ajax({
        url: "ajax_handler.php",
        type: "POST",
        data: {
          action: "fetch_medicamentos",
          csrf_token: $('input[name="csrf_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#medicamento").html(response.data.medicamentosOptions);
            $("#edit_medicamento").html(response.data.medicamentosOptions);
          } else {
            alert("Error: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          alert("Error al cargar medicamentos: " + error);
        },
      });
    }

    // Fetch Medications in Transit and Confirmed Medications
    function fetchSurtidos() {
      $.ajax({
        url: "ajax_handler.php",
        type: "POST",
        data: {
          action: "select_paciente",
          csrf_token: $('input[name="csrf_token"]').val(),
        },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            // Update Insumos Table (for reference, not modified)
            $("#tablaSurtidos").html(response.data.tablaSurtidos);

            // Update Medications Table
            $("#tablaSurtidosMed").html(response.data.tablaSurtidosMed);

            // Reinitialize DataTables after updating tables
            tableME.clear().draw();
            tableMEDC.clear().draw();

            // Parse Medications in Transit (exampleME)
            var tablaSurtidosMed = $(response.data.tablaSurtidosMed);
            if (tablaSurtidosMed.find("tbody tr").length > 0) {
              tablaSurtidosMed.find("tbody tr").each(function () {
                var $row = $(this);
                var id = $row.find("td").eq(0).text();
                var paciente = $row.find("td").eq(1).text();
                var medicamento = $row.find("td").eq(2).text();
                var cantidad = $row.find("td").eq(3).text();
                var total = $row.find("td").eq(4).text();
                var acciones = $row.find("td").eq(5).html();

                tableME.row
                  .add([
                    id,
                    new Date().toLocaleDateString("es-ES"),
                    new Date().toLocaleTimeString("es-ES"),
                    medicamento,
                    "", // Dosis (to be populated if needed)
                    "", // Vía
                    "", // Otros
                    cantidad,
                    "", // Cantidad Utilizada
                    acciones,
                  ])
                  .draw();
              });
            }

            // Parse Confirmed Medications (exampleMEDC)
            // Assuming confirmed medications are fetched separately or handled similarly
            // Update as needed based on backend response
          } else {
            alert("Error: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          alert("Error al cargar datos: " + error);
        },
      });
    }

    // Populate Medications on page load
    fetchMedicamentos();
    fetchSurtidos();

    // Handle Medication Selection for Stock Check
    $("#medicamento").on("change", function () {
      var itemId = $(this).val();
      if (itemId) {
        $.ajax({
          url: "ajax_handler.php",
          type: "POST",
          data: {
            action: "select_medicamento",
            medicamento: itemId,
            csrf_token: $('input[name="csrf_token"]').val(),
          },
          dataType: "json",
          success: function (response) {
            if (response.success) {
              $("#total_existencias_medicamento").val(
                response.data.totalExistencias
              );
            } else {
              alert("Error: " + response.message);
              $("#total_existencias_medicamento").val("");
            }
          },
          error: function (xhr, status, error) {
            alert("Error al verificar existencias: " + error);
            $("#total_existencias_medicamento").val("");
          },
        });
      } else {
        $("#total_existencias_medicamento").val("");
      }
    });

    // Handle Edit Medication Selection for Stock Check
    $("#edit_medicamento").on("change", function () {
      var itemId = $(this).val();
      if (itemId) {
        $.ajax({
          url: "ajax_handler.php",
          type: "POST",
          data: {
            action: "select_medicamento",
            medicamento: itemId,
            csrf_token: $('input[name="csrf_token"]').val(),
          },
          dataType: "json",
          success: function (response) {
            if (response.success) {
              $("#edit_total_existencias_medicamento").val(
                response.data.totalExistencias
              );
            } else {
              alert("Error: " + response.message);
              $("#edit_total_existencias_medicamento").val("");
            }
          },
          error: function (xhr, status, error) {
            alert("Error al verificar existencias: " + error);
            $("#edit_total_existencias_medicamento").val("");
          },
        });
      } else {
        $("#edit_total_existencias_medicamento").val("");
      }
    });

    // Handle Add Medication Form Submission
    $("#agregar-medicamento-form").on("submit", function (e) {
      e.preventDefault();
      var formData = $(this).serialize();

      $.ajax({
        url: "ajax_handler.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#addUserModalME").modal("hide");
            $("#agregar-medicamento-form")[0].reset();
            $("#total_existencias_medicamento").val("");
            fetchSurtidos(); // Refresh tables
            alert("Medicamento agregado con éxito");
          } else {
            alert("Error: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          alert("Error al agregar medicamento: " + error);
        },
      });
    });

    // Handle Edit Medication Form Submission
    $("#editar-medicamento-form").on("submit", function (e) {
      e.preventDefault();
      var formData = $(this).serialize();

      $.ajax({
        url: "ajax_handler.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $("#editUserModalME").modal("hide");
            $("#editar-medicamento-form")[0].reset();
            $("#edit_total_existencias_medicamento").val("");
            fetchSurtidos(); // Refresh tables
            alert("Medicamento actualizado con éxito");
          } else {
            alert("Error: " + response.message);
          }
        },
        error: function (xhr, status, error) {
          alert("Error al actualizar medicamento: " + error);
        },
      });
    });

    // Handle Delete Medication
    $(document).on("submit", ".eliminar-medicamento-form", function (e) {
      e.preventDefault();
      var formData = $(this).serialize();

      if (confirm("¿Está seguro de que desea eliminar este medicamento?")) {
        $.ajax({
          url: "ajax_handler.php",
          type: "POST",
          data: formData,
          dataType: "json",
          success: function (response) {
            if (response.success) {
              fetchSurtidos(); // Refresh tables
              alert("Medicamento eliminado con éxito");
            } else {
              alert("Error: " + response.message);
            }
          },
          error: function (xhr, status, error) {
            alert("Error al eliminar medicamento: " + error);
          },
        });
      }
    });

    // Handle Delete Surtido (Confirmed Medication)
    $(document).on("submit", ".eliminar-surtido-form", function (e) {
      e.preventDefault();
      var formData = $(this).serialize();

      if (confirm("¿Está seguro de que desea eliminar este ítem surtido?")) {
        $.ajax({
          url: "ajax_handler.php",
          type: "POST",
          data: formData,
          dataType: "json",
          success: function (response) {
            if (response.success) {
              fetchSurtidos(); // Refresh tables
              alert("Ítem eliminado con éxito");
            } else {
              alert("Error: " + response.message);
            }
          },
          error: function (xhr, status, error) {
            alert("Error al eliminar ítem: " + error);
          },
        });
      }
    });

    // Handle Edit Button Click to Populate Edit Modal
    $(document).on("click", ".edit-medicamento-btn", function () {
      var index = $(this).data("index");
      var medicamento = $(this).data("medicamento");
      var dosis = $(this).data("dosis");
      var via = $(this).data("via");
      var otros = $(this).data("otros");
      var cantidad = $(this).data("cantidad");

      $("#editar_index").val(index);
      $("#edit_medicamento").val(medicamento);
      $("#edit_dosis_medicamento").val(dosis);
      $("#edit_via_medicamento").val(via);
      $("#edit_otros_medicamento").val(otros);
      $("#edit_cantidad_medicamento").val(cantidad);

      // Trigger stock check for selected medicamento
      if (medicamento) {
        $.ajax({
          url: "ajax_handler.php",
          type: "POST",
          data: {
            action: "select_medicamento",
            medicamento: medicamento,
            csrf_token: $('input[name="csrf_token"]').val(),
          },
          dataType: "json",
          success: function (response) {
            if (response.success) {
              $("#edit_total_existencias_medicamento").val(
                response.data.totalExistencias
              );
            } else {
              $("#edit_total_existencias_medicamento").val("");
            }
          },
          error: function (xhr, status, error) {
            $("#edit_total_existencias_medicamento").val("");
          },
        });
      }

      $("#editUserModalME").modal("show");
    });
  }); */
});
