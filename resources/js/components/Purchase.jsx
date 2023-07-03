import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";
import Select from 'react-select'

class Purchase extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            suppliers: [],
            suppliers_id: null,
            search: "",
            options: [],
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);
        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.loadProductsSelect = this.loadProductsSelect.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);

        this.handleChangePrice = this.handleChangePrice.bind(this);
        this.handleChangeExpiredDate = this.handleChangeExpiredDate.bind(this);

        this.addProductToCart = this.addProductToCart.bind(this);

        this.handleOnSupplierChange = this.handleOnSupplierChange.bind(this);
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadProductsSelect();
        this.loadSuppliers();
    }

    loadSuppliers() {
        axios.get(`/suppliers/json`).then((res) => {
            const suppliers = res.data;
           this.setState({
                suppliers: suppliers.map((supplier) => ({
                    value: supplier.id,
                    label: supplier.supplier_name,
                }))
           });
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

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnSupplierChange(event) {
        const suppliers_id = event.target.value;
        this.setState({ suppliers_id });
    }

    loadCart() {
        const cart = JSON.parse(localStorage.getItem("cart_purchase")) || [];
        this.setState({ cart });
    }
    handleChangeQty(product_id, qty) {
        if (qty < 1 || qty == 0) {
            console.log("delete " + product_id);
            this.handleClickDelete(product_id);
        } else if(qty > 0) {
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
    }

    getTotal(cart) {
        const total = cart.map((c) => c.pivot.quantity * c.purchase_price);
        return sum(total);
    }
    handleClickDelete(product_id) {
        const cart = this.state.cart.filter((c) => c.id !== product_id);
        this.setState({ cart });

        localStorage.setItem("cart_purchase", JSON.stringify(cart));
    }
    handleEmptyCart() {
        localStorage.removeItem("cart_purchase");
        this.setState({ cart: [] });
    }
    handleChangeSearch(event) {
        const search = event.label;
        this.loadProducts(search);
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
                            c.id === product.id
                        ) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    }),
                });
            } else {
                this.setState({
                    cart: [...this.state.cart, { ...product, pivot: { quantity: 1 } }],
                })
            }

            localStorage.setItem("cart_purchase", JSON.stringify([...this.state.cart, product]));
        }
    }

    handleClickSubmit() {
        Swal.fire({
            title: "Purchase Completed",
            showCancelButton: true,
            confirmButtonText: "Send",
            showLoaderOnConfirm: true,
            preConfirm: async () => {
                return await axios
                    .post("/admin/orders", {
                        supplier_id: this.state.suppliers_id,
                        cart: this.state.cart
                    })
                    .then((res) => {
                        localStorage.removeItem("cart_purchase");
                        this.loadCart();
                        this.setState({ suppliers_id: null });
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

    // handleChangePrice(id, event) {
    //     const price = event.target.value;
    //     console.log(price);
    // }

    // debounce for handleChangePrice
    debounce = (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    handleChangeExpiredDate = this.debounce((id, event) => {
        const expired_date = event.target.value;
        axios
            .post("/admin/update-expired-date", {
                id: id,
                expired_date: expired_date
            })
            .then((res) => {
                this.setState({
                    cart: this.state.cart.map((p) => {
                        if (p.id === id) {
                            p.expired_date = expired_date;
                        }
                        return p;
                    }),
                    products: this.state.products.map((p) => {
                        if (p.id === id) {
                            p.expired_date = expired_date;
                        }
                        return p;
                    })
                });
            });
    }, 600)

    handleChangePrice = this.debounce((id, event) => {
        const price = event.target.value;
        axios
            .post("/admin/update-purchase-price", {
                id: id,
                purchase_price: price
            })
            .then((res) => {
                this.setState({
                    cart: this.state.cart.map((p) => {
                        if (p.id === id) {
                            p.purchase_price = price;
                        }
                        return p;
                    }),
                    products: this.state.products.map((p) => {
                        if (p.id === id) {
                            p.purchase_price = price;
                        }
                        return p;
                    })
                });
            });
    }, 400)

    render() {
        const { cart, products, suppliers, barcode } = this.state;
        return (
            <div className="conatiner">
                <div className="row">
                    <div className="col-md-6 col-lg-6">
                        <div className="row mb-2">
                            <div className="col-md-12">
                                <Select options={suppliers}
                                    onChange={(selected) => {
                                        this.setState({ suppliers_id: selected.value });
                                    }}
                                />
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
                                            <th className="text-right">Harga Beli</th>
                                            <th className="text-right">Expired Date</th>
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
                                                {/* <td className="text-right">
                                                    {window.APP.currency_symbol}{" "}
                                                    {format_rupiah((c.purchase_price * c.pivot.quantity).toString())}
                                                </td> */}
                                                <td className="text-right">
                                                <input
                                                        type="text"
                                                        className="form-control"
                                                        defaultValue={c.purchase_price * c.pivot.quantity}
                                                        onChange={(event) => {
                                                            this.handleChangePrice(
                                                                c.id,
                                                                event
                                                            )
                                                        }}
                                                    />
                                                </td>
                                                <td className="text-right">
                                                <input
                                                        type="date"
                                                        className="form-control"
                                                        {
                                                            ...c.expired_date ? { 
                                                                defaultValue: new Date(c.expired_date).toISOString().split('T')[0]
                                                             } : {}
                                                        }
                                                        onChange={(event) => {
                                                            this.handleChangeExpiredDate(
                                                                c.id,
                                                                event
                                                            )
                                                        }}
                                                    />
                                                </td>
                                                <td>
                                                    <i
                                                        className="fas fa-trash text-danger"
                                                        role="button"
                                                        onClick={(event) =>
                                                            this.handleClickDelete(c.id)
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
                    <div className="col-md-6 col-lg-6">
                        <div className="mb-2">
                            <Select options={this.state.options} 
                                onChange={this.handleChangeSearch}
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

export default Purchase;

if (document.getElementById("purchase")) {
    ReactDOM.render(<Purchase />, document.getElementById("purchase"));
}
