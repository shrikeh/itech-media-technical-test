'use strict';

import * as React from 'react';

interface Props {
  screenName: string;
  imageUrl: string;
  text: string;
}

export class TimelineTweet extends React.Component<Props> {
  render () {
    const { screenName, imageUrl, text } = this.props;
    return (
      <div>
        <img src={imageUrl} alt={screenName}/>
        <div>{screenName}</div>
        <div>{text}</div>
      </div>
    )
  }
}
