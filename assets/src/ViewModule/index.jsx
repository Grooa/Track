import React from 'react';
import PropTypes from 'prop-types';

import ModuleApi from '../DisplayVideo/moduleApi';
import FeedbackSection from '../common/FeedbackSection';

import { fetchConfigProps } from '../common/config';

export default class ViewModule extends React.Component {
  constructor() {
    super();

    this.state = {
      loaded: false,
      module: {},
    };

    this.hasLoaded = this.hasLoaded.bind(this);
    this.hasModule = this.hasModule.bind(this);
  }

  componentDidMount() {
    ModuleApi.findById(this.props.moduleId)
      .then(data => this.setState({ loaded: true, module: data }))
      .catch((err) => {
        this.setState({ loaded: true });
        console.error(err);
      });
  }

  hasLoaded() {
    return this.state.loaded;
  }

  hasModule() {
    return Object.keys(this.state.module).length > 0;
  }

  render() {
    if (!this.hasLoaded()) {
      return <FeedbackSection
        type="loading"
        text="Loading" />;
    }

    if (!this.hasModule()) {
      return <FeedbackSection
        type="error"
        text="No such module for this course" />;
    }

    return <article className="course-module-page">
      <header className="title-area">
        <strong className="type">{this.state.module.type}</strong>
        <h1 className="title">{this.state.module.title}</h1>
      </header>

      <FeedbackSection
        type="must-login"
        title="Login to access"
        text="You will need to be logged into you account, before accessing this module"
        link={{ url: `${fetchConfigProps().baseUrl}login`, label: 'Login to user' }} />

      <div className="columns">

      </div>
    </article>;
  }
}

ViewModule.propTypes = {
  moduleId: PropTypes.number.isRequired,
};
