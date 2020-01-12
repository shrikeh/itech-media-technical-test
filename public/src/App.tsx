import * as React from "react";
import * as ReactDOM from "react-dom";
import { Twitter } from "./components/Twitter";

const e = React.createElement;

const domContainer = document.querySelector('#itech-media');

ReactDOM.render(e(Twitter), domContainer);
