'use strict';

import * as React from "react";
import { render } from "react-dom";
import { Timeline } from "@app/components/Timeline";

const domContainer = document.querySelector('#itech-media');

render(
  <Timeline />,
  domContainer
);
