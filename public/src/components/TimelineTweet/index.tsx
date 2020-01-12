'use strict';

import * as React from 'react';

interface Props {
  screenName: string;
  imageUrl: string;
  text: string;
}

export class Tweet extends React.Component<Props> {
  render () {
    const { screenName, imageUrl, text } = this.props;
    return (
      <div>
        <img src="{imageUrl}" />
        <div>{screenName}</div>
        <div>{text}</div>
      </div>
    )
  }
}
