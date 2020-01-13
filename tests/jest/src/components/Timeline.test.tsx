import React from "react";
import { shallow } from 'enzyme';
import { Timeline } from "@app/components/Timeline";

describe("<Timeline />", () => {
  it("the Twitter timeline", async () => {
    const timeline = shallow(<Timeline />)

    expect(timeline.text()).toEqual('Loading...');
  });
});
