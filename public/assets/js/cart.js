function setUpCart(cart) {
  const emptyCartMessage = cart.querySelector(".empty-cart-message"),
    cartItems = cart.querySelector(".items > tbody"),
    paymentMethodSelector = cart.querySelector(".payment-method-selector"),
    confirmOrderButton = cart.querySelector(".confirm-order-button"),
    totalRow = cartItems.querySelector(".cart-total"),
    totalElement = totalRow.querySelector(".total");

  document.addEventListener("click", function (event) {
    const addToCartButton = event.target.closest(".add-to-cart-btn");
    const removeFromCartButton = event.target.closest(".remove-from-cart-btn");

    if (addToCartButton) {
      const medicineToAddDataset = addToCartButton.closest("tr").dataset;

      if (isMedicineAlreadyAdded(medicineToAddDataset)) {
        return;
      }

      addMedicineToCart(medicineToAddDataset);

      adaptCart();
    } else if (removeFromCartButton) {
      removeFromCartButton.closest("tr").remove();

      adaptCart();
    }
  });

  function adaptCart() {
    if (cartItems.children.length > 1) {
      cartItems.parentElement.classList.remove("d-none");
      paymentMethodSelector.classList.remove("d-none");
      emptyCartMessage.classList.add("d-none");
      confirmOrderButton.removeAttribute("disabled");
      totalRow.classList.remove("d-none");
      updateTotal();
    } else {
      cartItems.parentElement.classList.add("d-none");
      paymentMethodSelector.classList.add("d-none");
      emptyCartMessage.classList.remove("d-none");
      confirmOrderButton.setAttribute("disabled", "");
      totalRow.classList.add("d-none");
    }
  }

  function isMedicineAlreadyAdded(medicineDataset) {
    for (const item of cartItems.children) {
      if (item.dataset.boxId === medicineDataset.boxId) {
        return true;
      }
    }

    return false;
  }

  function addMedicineToCart(medicineDataset) {
    const elements = getNeededCartItemElements();

    const [
      itemRow,
      medicineNameElement,
      unitPriceElement,
      quantityElement,
      quantityInput,
      boxIdInput,
      unitPriceInput,
      medicineNameInput,
      subTotalElement,
      removeItemButtonWrapper,
    ] = [
      elements["itemRow"],
      elements["medicineNameElement"],
      elements["unitPriceElement"],
      elements["quantityElement"],
      elements["quantityInput"],
      elements["boxIdInput"],
      elements["unitPriceInput"],
      elements["medicineNameInput"],
      elements["subTotalElement"],
      elements["removeItemButtonWrapper"],
    ];

    prepareRemoveItemElement(
      elements["removeItemButton"],
      elements["removeItemButtonIcon"],
      removeItemButtonWrapper
    );

    itemRow.setAttribute("data-box-id", medicineDataset.boxId);

    setRequiredAttributes([
      {
        element: boxIdInput,
        attributes: [
          { name: "type", value: "text" },
          { name: "name", value: `boxId[]` },
          { name: "hidden", value: "" },
        ],
      },
      {
        element: unitPriceInput,
        attributes: [
          { name: "type", value: "number" },
          { name: "name", value: `unitPrice[]` },
          { name: "step", value: "any" },
          { name: "hidden", value: "" },
        ],
      },
      {
        element: medicineNameInput,
        attributes: [
          { name: "type", value: "text" },
          { name: "name", value: `medicineName[]` },
          { name: "hidden", value: "" },
        ],
      },
      {
        element: quantityInput,
        attributes: [
          { name: "type", value: "number" },
          { name: "min", value: "1" },
          { name: "step", value: "1" },
          { name: "name", value: `quantity[]` },
          { name: "id", value: "quantity" },
          { name: "required", value: "" },
        ],
      },
    ]);

    quantityInput.addEventListener("change", updateSubtotal);
    quantityInput.addEventListener("change", updateTotal);

    prepareQuantityElement(quantityInput, quantityElement);

    quantityElement.addEventListener("keydown", preventFormSubmitOnEnter);

    setRequiredCSSClasses([
      { element: medicineNameElement, cssClasses: ["py-2"] },
      { element: unitPriceElement, cssClasses: ["unit-price", "py-2"] },
      { element: quantityElement, cssClasses: ["py-2"] },
      { element: subTotalElement, cssClasses: ["subtotal", "py-2"] },
      { element: removeItemButtonWrapper, cssClasses: ["py-2"] },
    ]);

    insertItem(
      itemRow,
      [
        medicineNameElement,
        unitPriceElement,
        quantityElement,
        boxIdInput,
        unitPriceInput,
        medicineNameInput,
        subTotalElement,
        removeItemButtonWrapper,
      ],
      totalRow
    );

    setRequiredDisplayValues([
      { element: medicineNameElement, value: medicineDataset.medicineName },
      { element: unitPriceElement, value: medicineDataset.price },
      { element: subTotalElement, value: medicineDataset.price },
    ]);

    setRequiredInputValues([
      { element: quantityInput, value: 1 },
      { element: boxIdInput, value: medicineDataset.boxId },
      { element: medicineNameInput, value: medicineDataset.medicineName },
      { element: unitPriceInput, value: +medicineDataset.price },
    ]);
  }

  function updateSubtotal(event) {
    const rowWrapper = event.target.parentElement.parentElement;

    const subtotal =
      +event.target.value *
      +rowWrapper.querySelector(".unit-price").textContent;

    rowWrapper.querySelector(".subtotal").textContent = subtotal.toFixed(2);
  }

  function updateTotal() {
    const subTotalElements = cartItems.querySelectorAll(".subtotal");

    let total = 0;

    for (const element of subTotalElements) {
      total += +element.textContent;
    }

    totalElement.textContent = total.toFixed(2);
  }

  function preventFormSubmitOnEnter(event) {
    if (event.key === "Enter") {
      event.preventDefault();
    }
  }

  function getNeededCartItemElements() {
    const elementsToCreate = [
        { tag: "tr", count: 1, names: ["itemRow"] },
        {
          tag: "td",
          count: 5,
          names: [
            "medicineNameElement",
            "unitPriceElement",
            "quantityElement",
            "subTotalElement",
            "removeItemButtonWrapper",
          ],
        },
        { tag: "button", count: 1, names: ["removeItemButton"] },
        { tag: "img", count: 1, names: ["removeItemButtonIcon"] },
        {
          tag: "input",
          count: 4,
          names: ["quantityInput", "boxIdInput", "unitPriceInput", "medicineNameInput"],
        },
      ],
      elements = {};

    for (let i = 0; i < elementsToCreate.length; i++) {
      const elementToCreate = elementsToCreate[i];

      for (let j = 0; j < elementToCreate.count; j++) {
        elements[elementToCreate.names[j]] = document.createElement(
          elementToCreate.tag
        );
      }
    }

    return elements;
  }

  function prepareRemoveItemElement(
    removeItemButton,
    removeItemButtonIcon,
    removeItemButtonWrapper
  ) {
    // since elements are of type object, we don't need to return. Mutating affects the original
    removeItemButton.setAttribute("type", "button");
    const buttonClasses = [
      "action-btn",
      "remove-from-cart-btn",
      "btn",
      "btn-primary",
      "rounded-circle",
      "d-flex",
      "align-items-center",
      "justify-content-center",
    ];
    removeItemButton.classList.add(...buttonClasses);

    removeItemButtonIcon.setAttribute("src", "/assets/icons/delete.svg");
    removeItemButton.append(removeItemButtonIcon);

    removeItemButtonWrapper.append(removeItemButton);
  }

  function prepareQuantityElement(quantityInput, quantityElement) {
    quantityElement.append(quantityInput);
  }

  function setRequiredAttributes(elements) {
    for (const element of elements) {
      setAttributes(element.element, element.attributes);
    }

    function setAttributes(element, attributes) {
      for (const attribute of attributes) {
        element.setAttribute(attribute.name, attribute.value);
      }
    }
  }

  function setRequiredCSSClasses(elements) {
    for (const element of elements) {
      setCSSClasses(element.element, element.cssClasses);
    }

    function setCSSClasses(element, cssClasses) {
      element.classList.add(...cssClasses);
    }
  }

  function setRequiredDisplayValues(elements) {
    for (const element of elements) {
      setDisplayValue(element.element, element.value);
    }

    function setDisplayValue(element, value) {
      element.textContent = value;
    }
  }

  function setRequiredInputValues(elements) {
    for (const element of elements) {
      setInputValue(element.element, element.value);
    }

    function setInputValue(element, value) {
      element.value = value;
    }
  }

  function insertItem(itemRow, elementsToAttachToRow, elementToInsertBefore) {
    itemRow.append(...elementsToAttachToRow);
    cartItems.insertBefore(itemRow, elementToInsertBefore);
  }
}

setUpCart(document.querySelector(".cart"));
