'use strict';

import * as React from 'react';

interface Props {}

interface State {
  error: null,
  isLoaded: boolean,
  items: Array<object>
};

export default class Twitter extends React.Component<Props, State> {
  state: State = {
    error: null,
    isLoaded: false,
    items: []
  }

  render() {

// Display a "Like" <button>
    return (
      <div>
        Tweets from my timeline:

      </div>
    );
  }
}
