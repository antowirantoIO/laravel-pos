import React, { Component } from "react";
import ReactDOM from 'react-dom';
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";
import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            customers: [],
            barcode: "",
            search: "",
            customer_id: "",
            type_payment: "",
            sales_type: "retail",
            showDueDate : false,
            showModal: false,
            options: [],
            productsAutoComplete: []
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.handleOnSalesTypeChange = this.handleOnSalesTypeChange.bind(this);

        this.handleOptionPay = this.handleOptionPay.bind(this);
        this.handleClose = this.handleClose.bind(this);
        this.handleShow = this.handleShow.bind(this);
        this.handleGroceryPayment = this.handleGroceryPayment.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.loadProductsSelect = this.loadProductsSelect.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadProductsSelect();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then((res) => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProductsSelect() {
        axios.get(`/admin/products`).then((res) => {
            const products = res.data.data;
            const options = products.map((p) => {
                return { value: p.barcode, label: p.name };
            })
            this.setState({ options });
        });
    }

    loadProductsAutoComplete(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const productsAutoComplete = res.data.data ?? [];
            this.setState({ productsAutoComplete });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const products = res.data.data ?? [];
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        this.setState({ barcode });
    }

    handleOnSalesTypeChange(event) {
        const sales_type = event.target.value;
        this.setState({ sales_type });
    }

    loadCart() {
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        this.setState({ cart });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            this.addProductToCart(barcode);
            this.setState({ barcode: "" });
        }
    }
    handleChangeQty(product_id, qty) {
        if (qty < 1) {
            this.handleClickDelete(product_id);
        };

        const cart = this.state.cart.map((c) => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ cart });
        if (!qty) return;

        localStorage.setItem("cart", JSON.stringify(cart));
    }

    getTotal(cart) {
        const total = cart.map((c) => c.pivot.quantity * c.price);
        return sum(total);
    }
    handleClickDelete(product_id) {
        const cart = this.state.cart.filter((c) => c.id !== product_id);
        this.setState({ cart });

        localStorage.setItem("cart", JSON.stringify(cart));
    }
    handleEmptyCart() {
        localStorage.removeItem("cart");
        this.setState({ cart: [] });
    }
    handleChangeSearch(event) {
        if(event.target.value) {
            this.loadProductsAutoComplete(event.target.value);
        }else
            this.setState({ productsAutoComplete: [] });

         if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find((p) => p.barcode === barcode);
        if (!!product) {
            // if product is already in cart
            let cart = this.state.cart.find((c) => c.id === product.id);
            if (!!cart) {
                // update quantity
                this.setState({
                    cart: this.state.cart.map((c) => {
                        if (
                            c.id === product.id &&
                            product.quantity > c.pivot.quantity
                        ) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    }),
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 1,
                            product_id: product.id,
                            user_id: 1,
                        },
                    };

                    this.setState({ cart: [...this.state.cart, product] });
                }
            }

            localStorage.setItem("cart", JSON.stringify([...this.state.cart, product]));
        }
    }

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }

    handleOptionPay(event) {
        if (event.target.value == "due_date") {
            this.setState({ showDueDate: true });
        }
        if(event.target.value == "cash") {
            this.setState({ showDueDate: false });
        }
        this.setState({ type_payment: event.target.value });
    }

    handleClose() {
        this.setState({ showModal: false });
    }

    handleShow() {
        this.setState({ showModal: true });
    }

    handleGroceryPayment = async (e) => {
        e.preventDefault();
        var type_payment = this.state.type_payment ?? "cash"
        var amount = this.getTotal(this.state.cart)
        var due_date = document.getElementById("due_date").value

        return await axios
        .post("/admin/orders", {
            customer_id: this.state.customer_id,
            amount: amount,
            due_date: due_date ?? null,
            cart: this.state.cart
        })
        .then((res) => {
            localStorage.removeItem("cart");
            this.loadCart();
            this.setState({ showModal: false });
            return res.data;
        })
        .catch((err) => {
            Swal.showValidationMessage(err.response.data.message);
        });
    }

    handleClickSubmit() {
        if (this.state.sales_type == "grocery") {
            this.handleShow();
        } else if (this.state.sales_type == "retail") {
            Swal.fire({
                title: "Received Amount",
                input: "text",
                inputValue: this.getTotal(this.state.cart),
                showCancelButton: true,
                confirmButtonText: "Send",
                showLoaderOnConfirm: true,
                preConfirm: (amount) => {
                    return axios
                        .post("/admin/orders", {
                            customer_id: this.state.customer_id,
                            cart: this.state.cart,
                            amount,
                        })
                        .then((res) => {
                            localStorage.removeItem("cart");
                            this.loadCart();
                            return res.data;
                        })
                        .catch((err) => {
                            Swal.showValidationMessage(err.response.data.message);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading(),
            }).then((result) => {
                if (result.value) {
                    this.loadCart();
                }
            });
        }
    }

    render() {
        const { cart, products, customers, barcode, productsAutoComplete } = this.state;
        return (
            <>
                <Modal aria-labelledby="contained-modal-title-vcenter" centered show={this.state.showModal} onHide={this.handleClose}>
                    <Modal.Header>
                        <Modal.Title>Choose Payment Method</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <form onSubmit={this.handleGroceryPayment}>
                            <div className="form-group">
                                <select className="form-control" onChange={this.handleOptionPay}>
                                    <option>Pilih Pembayaran</option>
                                    <option value="cash">Pembayaran Cash</option>
                                    <option value="due_date">Pembayaran Jatuh Tempo</option>
                                </select>
                            </div>
                            <div className="form-group">
                                <label htmlFor="amount">Amount</label>
                                <input
                                    type="text"
                                    className="form-control"
                                    id="amount"
                                    name="amount"
                                    value={this.getTotal(cart)}
                                />
                            </div>
                            <div className={`form-group ${this.state.showDueDate ? '' : 'd-none'} group-duedate`}>
                                <label htmlFor="due_date">Due Date</label>
                                <input
                                    type="number"
                                    placeholder="30 Hari"
                                    className="form-control"
                                    id="due_date"
                                    name="due_date"
                                />
                            </div>

                            <button type="submit" className="btn btn-primary">
                                Submit
                            </button>

                        </form>
                    </Modal.Body>
                </Modal>
                <div className="conatiner">
                    <div className="row">
                        <div className="col-md-6 col-lg-5">
                            <div className="row mb-2">
                                <div className="col-md-6 pr-0">
                                    <form onSubmit={this.handleScanBarcode}>
                                        <input
                                            type="text"
                                            className="form-control"
                                            placeholder="Scan Barcode..."
                                            value={barcode}
                                            onChange={this.handleOnChangeBarcode}
                                        />
                                    </form>
                                </div>
                                <div className="col-md-6">
                                    <select
                                        className="form-control"
                                        onChange={this.handleOnSalesTypeChange}
                                    >
                                        <option value="">Jenis Penjualan</option>
                                        <option value="retail">Eceran</option>
                                        <option value="grocery">Glosir</option>
                                    </select>
                                </div>
                            </div>
                            <div className="user-cart">
                                <div className="card overflow-auto">
                                    <table className="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>UoM</th>
                                                <th className="text-right">Price</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {cart.map((c) => (
                                                <tr key={c.id}>
                                                    <td>{c.name}</td>
                                                    <td style={{
                                                        display: "flex",
                                                        alignItems: "center",
                                                        gap: "3px"
                                                    }}>
                                                        <input
                                                            type="text"
                                                            className="form-control form-control-sm qty"
                                                            value={c.pivot.quantity}
                                                            onChange={(event) =>
                                                                this.handleChangeQty(
                                                                    c.id,
                                                                    event.target.value
                                                                )
                                                            }
                                                        />
                                                        <i
                                                            className="fas fa-minus-circle text-danger"
                                                            role="button"
                                                            onClick={(event) =>
                                                                this.handleChangeQty(
                                                                    c.id,
                                                                    c.pivot.quantity - 1
                                                                )
                                                            }
                                                        ></i>
                                                        <i
                                                            className="fas fa-plus-circle text-success"
                                                            role="button"
                                                            onClick={(event) =>
                                                                this.handleChangeQty(
                                                                    c.id,
                                                                    c.pivot.quantity + 1
                                                                )
                                                            }
                                                        ></i>
                                                    </td>
                                                    <td>{c.uom}</td>
                                                    <td className="text-right">
                                                        {window.APP.currency_symbol}{" "}
                                                        {format_rupiah((c.price * c.pivot.quantity).toString())}
                                                    </td>
                                                    <td>
                                                        <i
                                                            className="fas fa-trash text-danger"
                                                            role="button"
                                                            onClick={(event) =>
                                                                this.handleClickDelete(
                                                                    c.id
                                                                )
                                                            }
                                                        ></i>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div className="row">
                                <div className="col">Total:</div>
                                <div className="col text-right">
                                    {window.APP.currency_symbol} {format_rupiah(this.getTotal(cart).toString())}
                                </div>
                            </div>
                            <div className="row pb-3">
                                <div className="col">
                                    <button
                                        type="button"
                                        className="btn btn-danger btn-block"
                                        onClick={this.handleEmptyCart}
                                        disabled={!cart.length}
                                    >
                                        Cancel
                                    </button>
                                </div>
                                <div className="col">
                                    <button
                                        type="button"
                                        className="btn btn-primary btn-block"
                                        disabled={!cart.length}
                                        onClick={this.handleClickSubmit}
                                    >
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div className="col-md-6 col-lg-7">
                            <div className="mb-2 position-relative">
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder="Search Product"
                                    onKeyDown={this.handleChangeSearch}
                                    onChange={this.handleChangeSearch}
                                />
                                {productsAutoComplete && (
                                    <div className="bg-white w-100 shadow" style={{position: 'absolute', zIndex: '999999'}}>
                                        <div className="">
                                            {productsAutoComplete.map((item, index) => (
                                                <div
                                                onClick={() => {
                                                    this.addProductToCart(item.barcode)
                                                    this.setState({ productsAutoComplete: null });
                                                }}
                                                key={index} className="px-3 pt-3 hover-bg-list border-bottom" style={{cursor: 'pointer'}}>
                                                    <div style={{fontWeight: 'bold', fontSize: '18px'}}>{item.name}</div>
                                                    <p>Stok : {item.quantity}</p>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                            <div className="row">
                                {products.map((p) => (
                                    <div className="col-md-4 mt-3" key={p.id} role="button">
                                        <div
                                            onClick={() => this.addProductToCart(p.barcode)}
                                            className="card h-100"
                                        >
                                            <div className="card-body bg-hovered">
                                                <h5
                                                    style={
                                                        window.APP.warning_quantity > p.quantity
                                                            ? { color: "red" }
                                                            : {}
                                                    }
                                                >
                                                    <strong>
                                                        {p.name}
                                                    </strong>
                                                    <p className="text-muted mt-2">
                                                        Stock : {p.quantity}
                                                    </p>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </>

        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
