import React, { Component } from "react";
import ReactDOM from 'react-dom';
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

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
            sales_type: "retail",
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.handleOnSalesTypeChange = this.handleOnSalesTypeChange.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then((res) => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const products = res.data.data;
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
        axios.get("/admin/cart").then((res) => {
            const cart = res.data;
            this.setState({ cart });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then((res) => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        if(qty < 1) {
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

        axios
            .post("/admin/cart/change-qty", { product_id, quantity: qty })
            .then((res) => { })
            .catch((err) => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(cart) {
        const total = cart.map((c) => c.pivot.quantity * c.price);
        return sum(total);
    }
    handleClickDelete(product_id) {
        axios
            .post("/admin/cart/delete", { product_id, _method: "DELETE" })
            .then((res) => {
                const cart = this.state.cart.filter((c) => c.id !== product_id);
                this.setState({ cart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/cart/empty", { _method: "DELETE" }).then((res) => {
            this.setState({ cart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
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

            axios
                .post("/admin/cart", { barcode })
                .then((res) => {
                    // this.loadCart();
                    // console.log(res);
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }
    handleClickSubmit() {
        if(this.state.sales_type == "grocery") {
            
        } else if(this.state.sales_type == "retail") {
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
                            amount,
                        })
                        .then((res) => {
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
        const { cart, products, customers, barcode } = this.state;
        return (
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
                            <div className="card">
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
                        <div className="mb-2">
                            <input
                                type="text"
                                className="form-control"
                                placeholder="Search Product..."
                                onChange={this.handleChangeSearch}
                                onKeyDown={this.handleSeach}
                            />
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
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
